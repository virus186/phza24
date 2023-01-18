<?php

namespace App\Traits;

use PDF;

trait GeneratePdf
{
    function generate_pdf($view, $order) {
        $config = ['instanceConfigurator' => function($mpdf) {
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
        }];

    	$pdf = PDF::loadView($view, compact('order'), [], $config);
    	return $pdf->stream($order->order_number.'.pdf');
    }

    public function getPDF($view, $data, $title)
    {
        $config = ['instanceConfigurator' => function($mpdf) {
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
        }];

        $pdf = PDF::loadView($view, $data,[],$config);
        return $pdf->stream($title.'.pdf');
    }

}
