<?php
    require_once 'mailman.class.php';
$m=new Mailman();
$m->letter->add_to('baserzas@gmail.com', 'Alexxey');
//$m->letter->add_to('baser-zas@yandex.ru');
$m->letter->set_from('your@address.com', 'FROMMM');
$m->letter->set_subject('HI');
$m->letter->set_message('TEST
Buyer:eh01067
Auction #:300040962621
Offer:TURNIER KICKER mit KUGELLAGER - TISCHFUSSBALL - 81 KG

End time:2006-10-25 12:37:57
Price:100.00
Category featured: yes
');
//$m->letter->attach_file('1.rtf', 'application/rtf', '1.rtf');
print_r($m);
if ($m->send_via_smtp('mail.qualitradegmbh.com', 'widmer@qualitradegmbh.com', 'poland')) {
        echo 'ok';  
} else
        echo 'Not ok';

exit();
		$queue = new Mailman();

$queue->letter->add_to('baserzas@gmail.com', 'Alexxey');
//$queue->letter->add_to('address_2@xyz.net', '??? ??????????');
// ??? ?? ???????? ?????? add_cc, add_bcc
$queue->letter->set_from('your@address.com', 'FROMMM');
//$queue->letter->set_reply_to('something@foobar.com', '??? ?????-???? ???');
// ??????? ????????? ?????????
$queue->letter->set_message('bububu');
/* // ? ????? ? HTML-???
$html_code='...';
$queue->letter->set_message($html_code, text/html, 'windows-1251');
*/
// ????? ??????????? ????, ????? ??????????
//$queue->letter->attach($path_to_file);
//$queue->letter->attach_file('1.rtf', 'application/rtf', '1.rtf');
// ??? ???????? ?????-?????? ?????????
//$queue->letter->add_header($queue->letter->base64_placeholders(
//        'X-Mailer: #?#', '???? ???????? ??????.'));
// ????? ?????? ???????????? ? ???????? ???? ??????
$ltr=$queue->letter->fetch();

// ? ??? ?????? ????? ?????????? ????????? ? ????? ??????
// ????????? ?????? ?? ????? ?????????? ? ???????
if ($queue->send_via_smtp('mail.qualitradegmbh.com', 'widmer@qualitradegmbh.com', 'poland'))
        echo 'ok';
else
        echo 'Not ok';
?>