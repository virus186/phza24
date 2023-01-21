<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Botble\Ecommerce\Models\Invoice;
use Botble\Ecommerce\Repositories\Interfaces\InvoiceInterface;
use Illuminate\Http\Request;
use InvoiceHelper;
use SeoHelper;
use Theme;
use Throwable;

class InvoiceController extends Controller
{
    /**
     * @return string
     * @throws Throwable
     */
    public function index()
    {
        SeoHelper::setTitle(__('Invoices'));

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('My Profile'), route('public.account.dashboard'))
            ->add(__('Manage Invoices'));

        return '';
    }

    /**
     * @param $id
     * @param InvoiceInterface $invoiceRepository
     * @return \Response
     */
    public function show($id, InvoiceInterface $invoiceRepository)
    {
        $invoice = $invoiceRepository->findOrFail($id);

        abort_unless($this->canViewInvoice($invoice), 404);

        $title = __('Invoice detail :code', ['code' => $invoice->code]);

        page_title()->setTitle($title);

        SeoHelper::setTitle($title);

        return Theme::scope(
            'ecommerce.customers.invoices.detail',
            compact('invoice'),
            'plugins/ecommerce::themes.customers.invoices.detail'
        )->render();
    }

    /**
     * @param int $invoiceId
     * @param Request $request
     * @param InvoiceInterface $invoiceRepository
     * @return \Response
     */
    public function getGenerateInvoice(int $invoiceId, Request $request, InvoiceInterface $invoiceRepository)
    {
        $invoice = $invoiceRepository->findOrFail($invoiceId);

        abort_unless($this->canViewInvoice($invoice), 404);

        if ($request->input('type') === 'print') {
            return InvoiceHelper::streamInvoice($invoice);
        }

        return InvoiceHelper::downloadInvoice($invoice);
    }

    /**
     * @param Invoice $invoice
     * @return bool
     */
    protected function canViewInvoice(Invoice $invoice): bool
    {
        return auth('customer')->id() == $invoice->payment->customer_id;
    }
}
