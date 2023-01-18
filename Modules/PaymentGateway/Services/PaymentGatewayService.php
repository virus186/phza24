<?php
namespace Modules\PaymentGateway\Services;

use Illuminate\Support\Facades\Validator;
use \Modules\PaymentGateway\Repositories\PaymentGatewayRepository;


class PaymentGatewayService
{
    protected $paymentGatewayRepository;

    public function __construct(PaymentGatewayRepository  $paymentGatewayRepository)
    {
        $this->paymentGatewayRepository = $paymentGatewayRepository;
    }

    public function gateway_activations()
    {
        return $this->paymentGatewayRepository->gateway_activations();
    }

    public function seller_payment_gateway(){
        return $this->paymentGatewayRepository->seller_payment_gateway();
    }

    public function gateway_active()
    {
        return $this->paymentGatewayRepository->gateway_active();
    }

    public function update_gateway_credentials($data)
    {

        return $this->paymentGatewayRepository->update_gateway_credentials($data);
    }

    public function global_setting_activation($data)
    {
        return $this->paymentGatewayRepository->global_setting_activation($data);
    }

    public function update_activation($data)
    {
        return $this->paymentGatewayRepository->update_activation($data);
    }

    public function findById($id)
    {
        return $this->paymentGatewayRepository->findById($id);
    }

    public function update($status){
        return $this->paymentGatewayRepository->update($status);
    }

}
