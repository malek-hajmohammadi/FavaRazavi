$receivers=array();
$receivers[0]=array();
$receivers[0]['type'] = 2; ////
/*
  * uid modir amel 1636
  * rid 2296
  */
$receivers[0]['uid'] = 1;
$receivers[0]['rid'] = 666;
$receivers[0]['oid'] = 'null';
$receivers[0]['oname'] = '';
$receivers[0]['isCC'] = 0;   ///رو نوشت باشه یه/
/ 1 باشه رونوشت
/ 0 ارجاع معمولی
$newRefer = DocRefer::ReferDocRefer($referID, $receivers, 'احتراما جهت استحضار');