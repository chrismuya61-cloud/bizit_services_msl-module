<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
 *  ==============================================================================
 *  Author  : Mian Saleem
 *  Email   : saleem@tecdiary.com
 *  For     : PHP QR Code
 *  Web     : http://phpqrcode.sourceforge.net
 *  License : open source (LGPL)
 *  ==============================================================================
 */

//  use chillerlan\QRCode\QRCode;
//  use chillerlan\QRCode\QROptions;
 
 class Tec_qrcode {
     public function generate($text, $path) {
         // Set QR code options
         $options = new QROptions([
             'eccLevel' => QRCode::ECC_L,
             'outputType' => QRCode::OUTPUT_IMAGE_PNG,
             'imageBase64' => false,
         ]);
 
         // Generate the QR code
         $qrcode = new QRCode($options);
         $qrcode->render($text, $path);
     }
 }
 