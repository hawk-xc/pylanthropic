<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\Program;
use App\Models\Donatur;

use App\Http\Controllers\WaBlastController;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }


    /**
     * Auto FU Donasi untuk pembayaran gopay, karena biasa gak lebih dari 3 menit sudah terbayar
     */
    public function donateFu1Gopay()
    {
        $now        = date('Y-m-d H:i:s');
        $date_start = date('Y-m-d H:i:s', strtotime($now.'-13 minutes'));
        $date_end   = date('Y-m-d H:i:s', strtotime($now.'-3 minutes'));

        $trans = Transaction::where('status', '<>', 'success')->where('payment_type_id', 6)
                ->where('created_at', '>=', $date_start)->where('created_at', '<=', $date_end)
                ->orderBy('created_at', 'asc')->get();
        $chat_count = 0;
        foreach ($trans as $key => $v) {
            if($chat_count<5) {         // agar 4 chat maksimal dalam 1 waktu 
                $count_fu = \App\Models\Chat::where('type', 'fu_trans')->where('transaction_id', $v->id)->select('id')->count();
                if($count_fu==0){ // hanya yg belum di chat yg akan dikirim

                    $program = Program::where('id', $v->program_id)->select('id', 'title')->first();
                    $donatur = Donatur::where('id', $v->donatur_id)->select('id', 'name', 'telp')->first();
                    $name    = ', *'.ucwords(trim($donatur->name)).'* ';

                    $chat    = 'Selangkah lagi kebaikan Anda'.$name.'akan dirasakan untuk program
*'.ucwords($program->title).'*
Dengan donasi yang Anda berikan sebesar *Rp '.str_replace(',', '.', number_format($v->nominal_final)).'*

bisa melalui :
Link Gopay : '.$v->midtrans_url;

                    $chat2   = $chat.'

atau melalui :
Transfer BSI - 7855555667
Transfer BRI - 041001001007307
Transfer BNI - 1859941829
Transfer Mandiri - 1370023737469
Transfer BCA - 4561399292
a/n *Yayasan Bantu Beramal Bersama*

melalui QRIS
https://bantubersama.com/public/QRIS.png

Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';
                
//                     $chat2   = $chat.'

// atau melalui :
// Transfer BSI - 7233152069
// Transfer BRI - 041001000888302
// Transfer BNI - 7060505013
// Transfer Mandiri - 1370022225276
// Transfer BCA - 4561363999
// a/n *Yayasan Bantu Bersama Sejahtera*

// melalui QRIS
// https://bantubersama.com/public/qris-babe.png

// Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
// Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';

                    (new WaBlastController)->sentWA($donatur->telp, $chat2, 'fu_trans', $v->id, $donatur->id, $program->id);

                    // count kirim chat, agar tidak lebih dari 4 chat dalam 1 waktu kirim
                    $chat_count++;
                }
            }
        }
        echo "Finish";
    }
    
    
    /**
     * Auto FU Donasi untuk pembayaran selain gopay dan qris dan BCA, karena biasa gak lebih dari 20 menit sudah dicek oleh checkmutation
     */
    public function donateFu1BankTransfer()
    {
        $now        = date('Y-m-d H:i:s');
        $date_start = date('Y-m-d H:i:s', strtotime($now.'-40 minutes'));
        $date_end   = date('Y-m-d H:i:s', strtotime($now.'-20 minutes'));

        $trans = Transaction::where('status', 'draft')
                ->where('created_at', '>=', $date_start)->where('created_at', '<=', $date_end)
                ->where('payment_type_id', '<>', 6)->where('payment_type_id', '<>', 5)->where('payment_type_id', '<>', 1);

        // jika lebih dari jam 22 dan diatas jam 6:30 checkmutataion tidak bisa cek mutasi BSI
        if(strtotime(date('H:i:s'))>=strtotime('21:45:00') && strtotime(date('H:i:s'))<=strtotime('06:30:00')) {echo 'IN BSI <br>';
            $trans = $trans->where('payment_type_id', '<>', 2);
        }

        // jika lebih dari jam 22 dan diatas jam 01:30 checkmutataion tidak bisa cek mutasi BRI
        if(strtotime(date('H:i:s'))>=strtotime('21:45:00') && strtotime(date('H:i:s'))<=strtotime('01:30:00')) {echo 'IN BRI <br>';
            $trans = $trans->where('payment_type_id', '<>', 4);
        }

        $trans = $trans->orderBy('created_at', 'asc')->get();

        $chat_count = 0;
        foreach ($trans as $key => $v) {
            if($chat_count<5) {         // agar 4 chat maksimal dalam 1 waktu 
                $count_fu = \App\Models\Chat::where('type', 'fu_trans')->where('transaction_id', $v->id)->select('id')->count();
                if($count_fu==0){ // hanya yg belum di chat yg akan dikirim

                    $program = Program::where('id', $v->program_id)->select('id', 'title')->first();
                    $donatur = Donatur::where('id', $v->donatur_id)->select('id', 'name', 'telp')->first();
                    $name    = ', *'.ucwords(trim($donatur->name)).'* ';

                    $chat    = 'Selangkah lagi kebaikan Anda'.$name.'akan dirasakan untuk program
*'.ucwords($program->title).'*
Dengan donasi yang Anda berikan sebesar *Rp '.str_replace(',', '.', number_format($v->nominal_final)).'*';

                    $chat2   = $chat.'

atau melalui :
Transfer BSI - 7855555667
Transfer BRI - 041001001007307
Transfer BNI - 1859941829
Transfer Mandiri - 1370023737469
Transfer BCA - 4561399292
a/n *Yayasan Bantu Beramal Bersama*

melalui QRIS
https://bantubersama.com/public/QRIS.png

Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';
                
//                     $chat2   = $chat.'

// atau melalui :
// Transfer BSI - 7233152069
// Transfer BRI - 041001000888302
// Transfer BNI - 7060505013
// Transfer Mandiri - 1370022225276
// Transfer BCA - 4561363999
// a/n *Yayasan Bantu Bersama Sejahtera*

// melalui QRIS
// https://bantubersama.com/public/qris-babe.png

// Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
// Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';

                    (new WaBlastController)->sentWA($donatur->telp, $chat2, 'fu_trans', $v->id, $donatur->id, $program->id);

                    // count kirim chat, agar tidak lebih dari 4 chat dalam 1 waktu kirim
                    $chat_count++;
                }
            }
        }
        echo "Finish";
    }


    /**
     * Auto FU Donasi yg belum dibayar dan sudah pernah chat 1 kali, jadi ini chat ke dua kalinya yg dijalankan melalui CronJob
     */
    public function donateFu2Sc()
    {
        $now        = date('Y-m-d H:i:s');
        $now        = date('Y-m-d H:i:s', strtotime($now.'-1 days'));
        $date_start = date('Y-m-d H:i:s', strtotime($now.'-10 minutes'));
        $date_end   = date('Y-m-d H:i:s', strtotime($now.'+10 minutes'));

        $trans = Transaction::where('status', '<>', 'success')->where('created_at', '>=', $date_start)->where('created_at', '<=', $date_end)
                ->orderBy('created_at', 'asc')->get();
        $chat_count = 0;

        foreach ($trans as $key => $v) {
            if($chat_count<5) {         // agar 4 chat maksimal dalam 1 waktu 
                $count_fu = \App\Models\Chat::where('type', 'fu_trans')->where('transaction_id', $v->id)->select('id')->count();
                if($count_fu==1){

                    // kirim chat FU ke-2
                    $program = Program::where('id', $v->program_id)->select('id', 'title')->first();
                    $donatur = Donatur::where('id', $v->donatur_id)->select('id', 'name', 'telp')->first();
                    $name    = ', *'.ucwords(trim($donatur->name)).'* ';

                    $chat    = 'Selangkah lagi kebaikan Anda'.$name.'akan dirasakan untuk program
*'.ucwords($program->title).'*
Dengan donasi yang Anda berikan sebesar *Rp '.str_replace(',', '.', number_format($v->nominal_final)).'*

bisa melalui :
Transfer BSI - 7855555667
Transfer BRI - 041001001007307
Transfer BNI - 1859941829
Transfer Mandiri - 1370023737469
Transfer BCA - 4561399292
a/n *Yayasan Bantu Beramal Bersama*

melalui QRIS
https://bantubersama.com/public/QRIS.png

Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';

                    (new WaBlastController)->sentWA($donatur->telp, $chat, 'fu_trans', $v->id, $donatur->id, $program->id);

                    // count kirim chat, agar tidak lebih dari 4 chat dalam 1 waktu kirim
                    $chat_count++;
                }
            }
        }
        echo "Finish";
    }

    
    /**
     * Cek WA Aktif
     */
    public function talentWACheck()
    {
        $data = Donatur::select('id', 'telp')->whereNull('wa_check')->whereNull('wa_inactive_since')->orderBy('id','asc')->limit(4)->get();

        foreach($data as $v){
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/check_number');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => env('TOKEN_RWA'),
                'number'  => $telp
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $response = json_decode($response);
            $now      = date('Y-m-d H:i:s');
            
            if($response->result=='true'){
                $update = Donatur::select('id')->where('id', $v->id);
                if($response->onwhatsapp=='true'){
                    $update->update(['wa_check' => $now, 'wa_inactive_since' => null]);
                } else {
                    $update->update(['wa_check' => $now, 'wa_inactive_since' => $now]);
                }
            }
        }
        
        echo 'FINISH';
    }


    /**
     * Cek WA Dorman
     */
    public function waDorman()
    {
        $data = Donatur::where('last_donate_paid', '<=', '2023-08-11 00:00:00')->where('last_donate_paid', '>=', '2023-03-01 00:00:00')
                // ->where('wa_campaign', '!=', 'dorman-25-08-2023')
                ->whereNull('wa_campaign')
                ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->orderBy('last_donate_paid', 'asc')->limit(4)->get();

        foreach($data as $v){
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // belum dibuat logic jika ternyata program sebelumnya sudah berakhir / tidak publish
            $trans = \App\Models\Transaction::select('program.id', 'title', 'slug')->join('program', 'program_id', 'program.id')
                    ->where('transaction.status', 'success')->where('donatur_id', $v->id)->orderBy('transaction.created_at', 'DESC')->first();

            $chat  = 'Perkenalkan saya Isna dari *Bantubersama*, semoga sehat selalu buat Kak *'.ucwords($v->name).'* aamiin..

Program yang Anda donasikan sebelumnya :
*'.ucwords($trans->title).'*

Masih terus berjalan hingga hari ini

Yuk kembali kita manfaatkan lagi kesempatan ini untuk terlibat aksi nyata dalam kebaikan.
Melalui link dibawah ini

https://bantubersama.com/'.$trans->slug.'

Kepedulian kita masih terus dinantikan, oleh mereka yang membutuhkan.';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => env('TOKEN_RWA'),
                'number'  => $telp,
                'message' => $chat,
                'date'    => date('Y-m-d'),
                'time'    => date('H:i'),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $response = json_decode($response);
            $now      = date('Y-m-d H:i:s');
            
            if($response->result=='true'){
                $update = Donatur::select('id')->where('id', $v->id);
                $update->update(['wa_campaign' => 'dorman-25-08-2023']);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp'        => $telp,
                'text'           => $chat,
                'token'          => env('TOKEN_RWA'),
                'vendor'         => 'RuangWA',
                'url'            => 'https://app.ruangwa.id/api/send_message',
                'type'           => 'repeat_donate',
                'transaction_id' => null,
                'donatur_id'     => $v->id,
                'program_id'     => $trans->id
            ]);
        }
        
        echo 'FINISH';
    }


    /**
     * Cek WA Dorman
     */
    public function waSummaryDonate()
    {
        $data = Donatur::where('sum_donate_paid', '>', 0)->whereNull('wa_campaign')
                ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->orderBy('sum_donate_paid', 'desc')->limit(4)->get();

        foreach($data as $v){
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // belum dibuat logic jika ternyata program sebelumnya sudah berakhir / tidak publish
            $nominal_final = \App\Models\Transaction::select('nominal_final')->where('created_at', '<', '2023-09-01 00:00:00')
                            ->where('transaction.status', 'success')->where('donatur_id', $v->id)->sum('nominal_final');

            $chat  = 'Salam peduli, sehat dan bahagia selalu buat Anda

Terima kasih atas donasi yang telah diberikan dan sudah menjadi bagian dari pelopor *Misi Kebaikan Bantubersama.com*

Rekap donasi Anda bulan Agustus 2023 sebesar *Rp.'.str_replace(',', '.', number_format($nominal_final)).'*
Semoga jiwa kepedulian dan komitmen membantu sesama terus membersamai Anda

Mari terus lanjutkan langkah positif ini untuk membantu sesama, kepedulian Anda masih terus dinantikan bagi mereka yang membutuhkan.

Yuk donasi kembali dengan klik tautan ini

https://bantubersama.com

Terimakash';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => env('TOKEN_RWA'),
                'number'  => $telp,
                'message' => $chat,
                'date'    => date('Y-m-d'),
                'time'    => date('H:i'),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $response = json_decode($response);
            $now      = date('Y-m-d H:i:s');
            
            if($response->result=='true'){
                $update = Donatur::select('id')->where('id', $v->id);
                $update->update(['wa_campaign' => 'dorman-25-08-2023']);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp'        => $telp,
                'text'           => $chat,
                'token'          => env('TOKEN_RWA'),
                'vendor'         => 'RuangWA',
                'url'            => 'https://app.ruangwa.id/api/send_message',
                'type'           => 'info',
                'transaction_id' => null,
                'donatur_id'     => $v->id,
                'program_id'     => null
            ]);
        }
        
        echo 'FINISH';
    }


    /**
     * Broadcast to Donatur about specific program
     */
    public function waProgramSpecific(Request $request)
    {
        // $donatur_done = \App\Models\Chat::where('program_id', 33)->where('text', 'like', 'salam%')->groupBy('donatur_id')->pluck('donatur_id');
        // $donatur      = Donatur::whereNotIn('id', $donatur_done)
        //                 // ->where('id', 201)->orWhere('id', 208)              // for testing send to Ulul & Isna
        //                 // ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->get();
        //                 ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->update([
        //                     'wa_campaign' => '-'
        //                 ]);
                        
                        
        // print_r($donatur_done);
        // echo count($donatur).'<br>';
        // foreach($donatur as $k => $v) {
        //     echo $v->name.' | '.$v->telp.'<br>';
        // }
        // die(' finish');


        $campaign   = 'palestina';
        $program_id = 33;
        // $program    = \App\Models\Program::->where('id', $program_id)->first();

        $donatur    = Donatur::where('wa_campaign', '<>', $campaign)->where('created_at', '<', date('Y-m-d', strtotime(date('Y-m-d').'-4 day')))
                    // ->where('id', 201)->orWhere('id', 208)              // for testing send to Ulul & Isna
                    ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->orderBy('id', 'asc')->limit(4)->get();

        foreach($donatur as $v){
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            $chat  = 'Salam *'.ucwords($v->name).'* Donatur #Bantubersama,
*Darurat kemanusiaan masih berlanjut sampai hari ini di Gaza, Palestina*

Korban telah mencapai 9.277 yang 3.677 merupakan anak-anak meninggal dunia, 2.405 perempuan serta 1.200 anak-anak masih tertimbun reruntuhan.
Kebutuhan mendesak obat-obatan, makanan siap saji, dan bantuan emergency lainnya.

Ayo satukan niat untuk memberi harapan kepada saudara kita di Gaza Palestina dengan klik donasi berikut

https://bantubersama.com/bantupalestina';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => env('TOKEN_RWA'),
                'number'  => $telp,
                'message' => $chat,
                'date'    => date('Y-m-d'),
                'time'    => date('H:i'),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $response = json_decode($response);
            $now      = date('Y-m-d H:i:s');
            
            if($response->result=='true'){
                Donatur::select('id')->where('id', $v->id)->update([
                    'wa_campaign' => $campaign, 
                    'updated_at'  => date('Y-m-d H:i:s')
                ]);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp'        => $telp,
                'text'           => $chat,
                'token'          => env('TOKEN_RWA'),
                'vendor'         => 'RuangWA',
                'url'            => 'https://app.ruangwa.id/api/send_message',
                'type'           => 'info',
                'transaction_id' => null,
                'donatur_id'     => $v->id,
                'program_id'     => $program_id
            ]);
        }
        
        echo 'FINISH';
    }

    /**
     * Update Sum Donate to program
     */
    public function sumDonate(Request $request)
    {
        // $page = $request->page;     // load per 100 program
        $last_update = date('Y-m-d H:i:s', strtotime('-30 minutes'));
        $program = Program::select('id')->where('is_publish', 1)->where('end_date', '>=', date('Y-m-d').' 00:00:00')
                    ->where('donate_sum_last_updated', '<', $last_update)->limit(200)->get();
        foreach($program as $v) {
            $sum_donate = Transaction::where('program_id', $v->id)->where('status', 'success')->sum('nominal_final');
            if($sum_donate > 0) {
                Program::where('id', $v->id)->update([ 'donate_sum'=>$sum_donate, 'donate_sum_last_updated'=>date('Y-m-d H:i:s') ]);
            }
        }
        echo "FINISH";
    }

    /**
     * Cancel Transaction status to draft when created at before 5 days ago dan more
     */
    public function updateTransactionStatus(Request $request)
    {
        $day5ago = date('Y-m-d', strtotime('-5 days')).' 00:00:00';

        Transaction::where('status', 'draft')->where('created_at', '<=', $day5ago)->update(['status'=>'cancel']);
        
        echo "FINISH";
    }
    
    /**
     * Update summary donate last_donate_paid, count_donate_paid, sum)donate_paid
     */
    public function donateUpdate()
    {
        // ini hanya untuk pertama kali saja / kalau sudah lama tidak dijalankan fungsi ini
        // agar bisa dijalankan kesemua data donatur
        // $dn      = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').'-120 minutes'));
        // $donatur = Donatur::select('id')
        //             ->where(function ($q) use($dn){ $q->whereNull('last_donate_paid')->orWhere('updated_at', '<=', $dn); })
        //             ->orderBy('id','asc')
        //             ->limit(3200)
        //             ->get();
        
        // agar efisien hanya donatur yg melakukan donasi dibayar 5 hari terakhir saja, meski ada donatur yg akan dijalankan beberapa kali
        $ld         = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').'-5 days'));
        $last_trans = \App\Models\Transaction::select('donatur_id')->where('status', 'success')->where('created_at', '>=', $ld)
                        ->groupBy('donatur_id')->orderBy('donatur_id', 'ASC')->get()->toArray();
        $donatur    = Donatur::select('id')->whereIn('id', $last_trans)->orderBy('id','asc')->get();

        foreach($donatur as $v){
            $trans = \App\Models\Transaction::selectRaw('count(id) as count_donate, sum(nominal_final) as sum_donate, MAX(created_at) as last_transaction')
                    ->where('donatur_id', $v->id)->where('status', 'success')
                    ->groupBy('donatur_id')
                    ->orderBy('created_at', 'DESC')->first();
            
            Donatur::where('id', $v->id)->update([ 
                    'sum_donate_paid'  => (isset($trans->sum_donate)) ? $trans->sum_donate : 0,
                    'count_donate_paid'=> (isset($trans->count_donate)) ? $trans->count_donate : 0,
                    'last_donate_paid' => (isset($trans->last_transaction)) ? $trans->last_transaction : null,
                    'updated_at'       => date('Y-m-d H:i:s')
            ]);
        }
        echo 'FINISH LAST DONATE PAID : '.count($donatur);
    }
}
