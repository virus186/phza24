<?php

namespace Botble\Ecommerce\Supports;

use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PDFHelper;
use Botble\Ecommerce\Models\Invoice;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Payment\Enums\PaymentStatusEnum;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class InvoiceHelper
{
    /**
     * @param Order $order
     * @return Invoice|\Illuminate\Database\Eloquent\Model
     */
    public function store(Order $order)
    {
        if ($order->invoice()->count()) {
            return $order->invoice()->first();
        }

        $address = $order->shippingAddress;

        if (\EcommerceHelper::isBillingAddressEnabled() && $order->billingAddress->id) {
            $address = $order->billingAddress;
        }

        $invoice = new Invoice([
            'reference_id' => $order->id,
            'reference_type' => Order::class,
            'customer_name' => $address->name ?: $order->user->name,
            'company_name' => '',
            'company_logo' => null,
            'customer_email' => $address->email ?: $order->user->email,
            'customer_phone' => $address->phone,
            'customer_address' => $address->address,
            'customer_tax_id' => null,
            'payment_id' => $order->payment->id,
            'status' => $order->payment->status,
            'paid_at' => $order->payment->status == PaymentStatusEnum::COMPLETED ? Carbon::now() : null,
            'tax_amount' => $order->tax_amount,
            'shipping_amount' => $order->shipping_amount,
            'discount_amount' => $order->discount_amount,
            'sub_total' => $order->sub_total,
            'amount' => $order->amount,
            'shipping_method' => $order->shipping_method,
            'shipping_option' => $order->shipping_option,
            'coupon_code' => $order->coupon_code,
            'discount_description' => $order->discount_description,
            'description' => $order->description,
        ]);

        $invoice->save();

        foreach ($order->products as $orderProduct) {
            $invoice->items()->create([
                'reference_id' => $orderProduct->product_id,
                'reference_type' => Product::class,
                'name' => $orderProduct->product_name,
                'description' => null,
                'image' => $orderProduct->product_image,
                'qty' => $orderProduct->qty,
                'sub_total' => $orderProduct->price,
                'tax_amount' => $orderProduct->tax_amount,
                'discount_amount' => 0,
                'amount' => $orderProduct->price * $orderProduct->qty + $orderProduct->tax_amount,
                'options' => json_encode($orderProduct->options),
            ]);
        }

        do_action(INVOICE_PAYMENT_CREATED, $invoice);

        return $invoice;
    }

    /**
     * @param Invoice $invoice
     *
     * @return PDFHelper
     */
    public function makeInvoicePDF(Invoice $invoice): PDFHelper
    {
        $fontsPath = storage_path('fonts');
        if (!File::isDirectory($fontsPath)) {
            File::makeDirectory($fontsPath);
        }

        $template = 'plugins/ecommerce::invoices.template';
        if (view()->exists('plugins/ecommerce/invoice::template')) {
            $template = 'plugins/ecommerce/invoice::template';
        }

        if (get_ecommerce_setting('invoice_support_arabic_language', 0) == 1) {
            $templateHtml = view($template, compact('invoice'))->render();

            $arabic = new Arabic();
            $p = $arabic->arIdentify($templateHtml);

            for ($i = count($p) - 1; $i >= 0; $i -= 2) {
                $utf8ar = $arabic->utf8Glyphs(substr($templateHtml, $p[$i - 1], $p[$i] - $p[$i - 1]));
                $templateHtml = substr_replace($templateHtml, $utf8ar, $p[$i - 1], $p[$i] - $p[$i - 1]);
            }

            $pdf = Pdf::loadHTML($templateHtml, 'UTF-8');
        } else {
            $pdf = Pdf::loadView($template, compact('invoice'), [], 'UTF-8');
        }

        return $pdf
            ->setPaper('a4')
            ->setWarnings(false)
            ->setOption('tempDir', storage_path('app'))
            ->setOption('logOutputFile', storage_path('logs/pdf.log'))
            ->setOption('isRemoteEnabled', true);
    }

    /**
     * @param Invoice $invoice
     *
     * @return string
     */
    public function generateInvoice(Invoice $invoice): string
    {
        $folderPath = storage_path('app/public');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath);
        }

        $invoice = $folderPath . '/invoice-' . $invoice->code . '.pdf';

        if (File::exists($invoice)) {
            return $invoice;
        }

        $this->makeInvoicePDF($invoice)->save($invoice);

        return $invoice;
    }

    /**
     * @param Invoice $invoice
     *
     * @return Response
     */
    public function downloadInvoice(Invoice $invoice): Response
    {
        return $this->makeInvoicePDF($invoice)->download('invoice-' . $invoice->code . '.pdf');
    }

    /**
     * @param Invoice $invoice
     *
     * @return Response
     */
    public function streamInvoice(Invoice $invoice): Response
    {
        return $this->makeInvoicePDF($invoice)->stream();
    }
}
