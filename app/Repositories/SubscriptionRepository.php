<?php
namespace App\Repositories;

use App\Events\VerifyNewsletter;
use App\Models\Subscription;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class SubscriptionRepository{

    protected $subscribe;

    public function __construct(Subscription $subscribe){
        $this->subscribe = $subscribe;
    }

    public function store($data){

        $details = $this->subscribe::create([
            'email' => $data['email'],
            'status' => 1,
            'is_verified' => 0,
            'verify_code' => Str::uuid()
        ]);

        if(!app('general_setting')->verify_on_newsletter){
            return 'subscribe_done';
        }else{
            Event::dispatch(new VerifyNewsletter($details));
            return 'verify_link_send';
        }
    }

    public function verify($data){
        $row = Subscription::where('email', $data['email'])->where('verify_code', $data['verify_code'])->first();
        if($row && $row->is_verified == 0){
            $row->update([
                'is_verified' => 1,
                'verify_code' => null
            ]);
            return 'success';
        }else{
            return 'invalid';
        }
    }
}