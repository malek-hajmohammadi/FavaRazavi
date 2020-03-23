<?php
$arCreditsRow[]=array();
$arCreditsRow[0]=array();
$arCreditsRow[0][0]="ردیف اعتباری اول بابت پرداخت هزینه";
$arCreditsRow[0][1]="500000";
$arCreditsRow[0][2]="232000";

$arCreditsRow[1]=array();
$arCreditsRow[1][0]="ردیف اعتباری دوم بابت پرداخت هزینه";
$arCreditsRow[1][1]="500000";
$arCreditsRow[1][2]="232000";

$arCreditsRow[2]=array();
$arCreditsRow[2][0]="ردیف اعتباری سوم بابت پرداخت هزینه";
$arCreditsRow[2][1]="500000";
$arCreditsRow[2][2]="232000";


Response::getInstance()->response=json_encode($arCreditsRow, JSON_FORCE_OBJECT);
