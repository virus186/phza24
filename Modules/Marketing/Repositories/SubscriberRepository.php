<?php
namespace Modules\Marketing\Repositories;

use App\Events\VerifyNewsletter;
use App\Models\Subscription;
use Illuminate\Support\Facades\Event;

class SubscriberRepository {

    public function getAll(){
        return Subscription::latest();
    }

    public function deleteById($id){
        return Subscription::findOrFail($id)->delete();
    }

    public function statusChange($data){
        return Subscription::findOrFail($data['id'])->update([
            'status' => $data['status']
        ]);
    }
    public function sendVerifyLink($id){
        $details = Subscription::where('id', $id)->where('is_verified', 0)->first();
        if($details){
            Event::dispatch(new VerifyNewsletter($details));
            return 'verify_link_send';
        }
        return 'invalid';
    }
}
