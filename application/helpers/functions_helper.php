<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('db_error_check')) {
    function db_error_check() {
        $CI =& get_instance();
        if ($CI->db->error()['code']) {
            die($CI->db->error()['code'] . ': ' . $CI->db->error()['message']);
        }
    }
}

if (!function_exists('labelBuilder')) {
    function labelBuilder($label, $args) {
        /*if ($label == NULL) {
            // var_dump($args['{{name}}']);
            if (strlen($args['{{name}}']) >= 20) {
                $name_label = "^FO160,15^FB200,2,0,C,0^A0N,24,24^FD{{name}}^FS";
                $storage_label = "^FO160,65^FB200,1,0,C,0^A0N,24,24^FD{{storage}}, {{sector}}^FS";
                $barcode_label = "^FS ^BY3,0,35 ^FO160,85^B8^FD{{barcode}}^FS";
            } else {
                $name_label = "^FO160,28^FB200,3,0,C,0^A0N,24,24^FD{{name}}^FS";
                $storage_label = "^FO160,52^FB200,3,0,C,0^A0N,24,24^FD{{storage}}, {{sector}}^FS";
                $barcode_label = "^FS ^BY3,0,35 ^FO160,85^B8^FD{{barcode}}^FS";
            }

            $logo = "^FO30,15^GFA,2025,2025,15,,:::::::R01FFE,R0JFC,Q03KF,Q07KFC,Q0LFE,P03MF,P07FF807FF8,P07FCI0FFC,P0FF8I03FE,O01FEJ01FE,O01FCK0FF,O03F8K07F,O03F8K03F8,O07FL03F8,O07FL01FC,O0FEL01FC,O0FEM0FC,O0FCM0FC,O0FCM0FE,:00IFJ0FCM0FEI03FFC,00IF8I0FCM0FEI03FFC,::00IF8I0FCM0FCI03FFC,00IF8I0FEM0FCI07FFC,00IF8I0FEL01FCI07FFC,007FF8I07EL01FCI07FFC,007FF8I07FL03F8I07FFC,007FFCI07F8K03F8I07FFC,007FFCI03F8K07F8I07FF8,007FFCI03FCK0FFJ0IF8,003FFEI01FEJ01FEJ0IF8,003FFEJ0FF8I03FEJ0IF8,003FFEJ07FCI0FFCI01IF,003IFJ07FF803FF8I01IF,001IFJ03MFJ03IF,001IF8I01LFEJ03FFE,001IF8J07KFCJ07FFE,I0IFCJ03KFK07FFE,I0IFCK0JFCK0IFC,I07FFEK01IFK01IFC,I07IFT01IF8,I03IFT03IF8,I03IF8S07IF,I01IFCS0JF,J0IFER01IFE,J0JFR03IFC,J07IF8Q07IFC,J03IFCQ0JF8,J03JFP01JF,J01JF8O07IFE,K0JFEO0JFC,K07JF8M03JFC,K03JFEM0KF8,K01KF8K07KF,L0LF8I03KFE,L07UF8,L03UF,M0TFE,M07SFC,M03SF,N0RFC,N03QF8,O0PFE,O03OF8,P0NFC,P01LFE,P03MF8,P0NFC,O03OF,O07OF8,O0PFE,N01QF,N07QF8,N0KF807JFC,N0JF8I07IFE,M01IFEK0JF,M03IF8K07IF,M07IFL01IF8,M07FFCM0IFC,M0IF8M07FFC,L01IFN03FFE,L01FFEN01IF,L03FFCO0IF,L03FFCO07FF,L03FF8O07FF8,L07FF8O03FF8,L07FFP03FFC,L07FFP01FFC,L0FFEP01FFC,L0FFEJ07F8J0FFC,L0FFEI01FFEJ0FFE,L0FFCI03IF8I0FFE,L0FFCI0FE0FCI0FFE,L0FFCI0F803EI0FFE,K01FFC001E001FI07FE,K01FFC003CI0FI07FE,L0FF8003CI078007FE,Q038I078,Q078I038,Q07J038,Q07J03C,L0EJ07J03CI01E,:L0FJ07J03CI01C,L0FJ0FJ01CI01C,L07J0FJ01EI03C,L078001EJ01EI078,L03C003CK0FI0F8,L03E007CK07C01F,L01F81F8K03F07E,M0JFL01IFC,M03FFCM0IF8,N0FFN03FE,,:::::::::::";

            $label = "^XA ^PW385 ^LH0,0" . $logo . $name_label . $storage_label . $barcode_label . "^XZ";



            /// Kábeles címke
            $name_label = "^FO95,33^FB200,3,0,C,0^A0N,24,24^FD{{name}}^FS";
            $storage_label = "^FO95,58^FB200,2,0,C,0^A0N,24,24^FD{{storage}},{{sector}}^FS";
            $barcode_label = "^FS ^BY3,0,35 ^FO60,140^B8I^FD{{barcode}}^FS";

            $logo = "^FO15,5^GFA,940,940,10,,::::O07C,N03FFC,N0JF,M01JF8,M07JFC,M0FF00FE,M0FC003F,L01F8001F8,L03FJ0F8,L03EJ07C,L03CJ07C,L07CJ03C,L07CJ03E,L078J03E,:03FC0078J03E007FC,::03FE007CJ03C007FC,03FE003CJ07C007FC,03FE003EJ07C007FC,03FE003EJ0F800FF8,03FE001FI01F800FF8,01FF001F8003FI0FF8,01FFI0FE007E001FF8,01FF8007F83FE001FF,00FF8003JFC001FF,00FFC001JFI03FF,00FFCI07FFEI07FE,007FEI01FFJ07FE,007FFP0FFC,003FFO01FFC,001FF8N03FF8,001FFCN07FF,I0FFEN0IF,I07FF8L01FFE,I07FFCL03FFC,I03IFL0IF8,I01IFCJ03IF,J0JFI01IFE,J07JFC3JFC,J01PF8,K0PF,K07NFC,K01NF8,L07LFE,L01LF8,M03JFC,M07JFE,L01LF8,L03LFC,L0MFE,K01NF,K03FFE00IF8,K07FF8001FFC,K07FEJ07FE,K0FFCJ03FF,J01FFK01FF,J01FFL0FF8,J03FEL07F8,J03FCL03FC,J03F8L03FC,J07F8L01FC,J07FM01FE,J07FI0FE001FE,J07F001FF800FE,J0FF003C3C00FE,J0FF00700E00FE,J0FF00E00600FE,N0C007,M01C003,:J06001C003I06,:J060018003800E,J070038001800C,J038038001C01C,J03C0FJ0E038,J01FFEJ07DF,K07FCJ03FE,K01FL0F8,,:::::::^FS";

            $label = "^XA ^PW560 ^LH0,0" . $logo . $name_label . $storage_label . $barcode_label . "^XZ";
        }*/

        foreach ($args as $key => $value) {
            $label = str_replace('{{'.$key.'}}', mb_strimwidth($value, 0, 20), $label);
        }

        $label = remove_accents($label);

        return $label;
    }
}
 
if (!function_exists('remove_accents')) {
    function remove_accents($string) {
        if ( !preg_match('/[\x80-\xff]/', $string) )
            return $string;
    
        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
        );
    
        $string = strtr($string, $chars);
    
        return $string;
    }
}
