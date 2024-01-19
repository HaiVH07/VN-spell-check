<?php
class Validate
{
    //==============================================================================
    function vn_spell_one_char($str)
    { // kiểm tra từ có một ký tự
        $rs = 0; // giả định ban đầu là sai chính tả

        // với từ chỉ có một từ thì để nó có nghĩa, nó phải thuộc về bộ nguyên âm một từ
        // phải khác ă và â, những nguyên âm cần có âm cuối
        if (in_array($str, $this->vna_vowel_lett()) && $str != "ă" && $str != "â") { // nó buộc phải thuộc về bộ nguyên âm đơn
            $rs = 1;
        }

        return $rs;
    }


    //==============================================================================
    function vn_spell_two_chars($str)
    { // kiểm tra từ có 2 ký tự
        $rs = 0;

        // nếu nó thuộc về mảng nguyên âm đôi, nó được xem là đúng chính tả && nó cần không được thuộc về mảng nguyên âm đôi cần âm cuối
        if (in_array($str, $this->vna_diphthongs()) && !in_array($str, $this->vna_final_csnt_req2()) && !in_array($str, $this->vna_final_voc_req2())) { // nó có thể thuộc về bộ nguyên âm đôi
            $rs = 1;
        }

        // các trường hợp gồm một nguyên âm và một phụ âm
        $flett = mb_substr($str, 0, 1, 'UTF-8'); // lấy ký tự đầu tiên
        $slett = mb_substr($str, 1, 1, 'UTF-8'); // lấy ký tự thứ hai

        // một phụ âm đầu và một nguyên âm cuối, tất cả chỉ có một ký tự
        // vna_first_csnt1() là mảng phụ âm đầu hợp lệ && vna_vowel_lett() là mảng nguyên âm có một ký tự
        // phải khác ă và â vì những nguyên âm đơn này buộc phải có thêm âm cuối
        if (in_array($flett, $this->vna_first_csnt1()) && in_array($slett, $this->vna_vowel_lett()) && $slett != "ă" && $slett != "â") {
            $rs = 1;
        }

        // một nguyên âm đầu và một phụ âm cuối, tất cả chỉ có một ký tự
        // vna_vowel_lett() nguyên âm một ký tự && vna_last_csnt1() phụ âm cuối một ký tự hợp lệ
        if (in_array($flett, $this->vna_vowel_lett()) && in_array($slett, $this->vna_last_csnt1())) {
            $rs = 1;
        }

        return $rs;
    }


    //==============================================================================
    function vn_spell_three_chars($str)
    { // kiểm tra từ có 3 ký tự
        $rs = 0;

        // nếu nó thuộc về mảng nguyên âm ba nó được xem là đúng chính tả & khác uyê cần phụ âm cuối
        if (in_array($str, $this->vna_triphthongs()) && $str != "uyê") { // nó có thể thuộc về bộ nguyên âm ba
            $rs = 1;
        }

        // các trường hợp khác gồm phụ âm và nguyên âm
        $flett = mb_substr($str, 0, 1, 'UTF-8'); // lấy ký tự đầu tiên
        $lslett = mb_substr($str, 1, 2, 'UTF-8'); // lấy 2 ký tự cuối

        $slett = mb_substr($str, 1, 1, 'UTF-8'); // lấy ký tự thứ hai

        $fslett = mb_substr($str, 0, 2, 'UTF-8'); // lấy 2 ký tự đầu tiên
        $llett = mb_substr($str, 2, 1, 'UTF-8'); // lấy ký tự cuối cùng


        // phụ âm đầu 1 ký tự, nguyên âm sau 2 ký tự
        // vna_first_csnt1() phụ âm đầu một ký tự được phép && vna_diphthongs() nguyên âm 2 ký tự && vna_final_voc_req2(), vna_final_csnt_req2() không thuộc về mảng nguyên âm đôi cần có phụ âm cuối
        if (in_array($flett, $this->vna_first_csnt1()) && in_array($lslett, $this->vna_diphthongs()) && !in_array($str, $this->vna_final_csnt_req2()) && !in_array($str, $this->vna_final_voc_req2())) {
            $rs = 1;
        }

        // phụ âm đầu 2 ký tự, nguyên âm sau 1 ký tự
        // vna_first_csnt2() phụ âm đầu 2 ký tự hợp lệ && vna_vowel_lett() nguyên âm một ký tự && nguyên âm đơn phải khác ă và â, cái cần âm cuối
        if (in_array($fslett, $this->vna_first_csnt2()) && in_array($llett, $this->vna_vowel_lett()) && $llett != "ă" && $llett != "â") {
            $rs = 1;
        }

        // nguyên âm đầu 1 ký tự, phụ âm sau 2 ký tự
        // vna_vowel_lett() mảng nguyên âm một ký tự && vna_last_csnt2() mảng phụ âm 2 ký tự hợp lệ
        if (in_array($flett, $this->vna_vowel_lett()) && in_array($lslett, $this->vna_last_csnt2())) {
            $rs = 1;
        }

        // nguyên âm đầu 2 ký tự, phụ âm sau 1 ký tự
        // vna_diphthongs() nguyên âm đầu 2 ký tự & vna_last_csnt1() phụ âm cuối 1 ký tự hợp lệ && vna_no_sound_end2() không thuộc về mảng nguyên âm đôi không được có âm cuối
        if (in_array($fslett, $this->vna_diphthongs()) && in_array($llett, $this->vna_last_csnt1()) && !in_array($fslett, $this->vna_no_sound_end2())) {
            $rs = 1;
        }

        // phụ âm đầu 1 ký tự, nguyên âm giữa 1 ký tự, phụ âm cuối 1 ký tự
        // vna_first_csnt1() phụ âm đầu 1 ký tự hợp lệ, vna_vowel_lett() nguyên âm 1 ký tự, vna_last_csnt1() phụ âm một ký tự hợp lệ
        if (in_array($flett, $this->vna_first_csnt1()) && in_array($slett, $this->vna_vowel_lett()) && in_array($llett, $this->vna_last_csnt1())) {
            $rs = 1;
        }

        return $rs;
    }


    //==============================================================================
    function vn_spell_four_chars($str)
    { // kiểm tra từ có 4 ký tự
        $rs = 0;

        $fslett = mb_substr($str, 0, 2, 'UTF-8'); // lấy 2 ký tự đầu tiên
        $lslett = mb_substr($str, 2, 2, 'UTF-8'); // lấy 2 ký tự cuối cùng

        $flett = mb_substr($str, 0, 1, 'UTF-8'); // lấy 1 ký tự đầu tiên
        $slett = mb_substr($str, 1, 1, 'UTF-8'); // lấy 1 ký tự thứ hai 
        $tlett = mb_substr($str, 2, 1, 'UTF-8'); // lấy 1 ký tự thứ ba

        $stlett = mb_substr($str, 1, 2, 'UTF-8'); // lấy 2 ký tự ở giữa 

        $ftlett = mb_substr($str, 0, 3, 'UTF-8'); // lấy 3 ký tự đầu tiên
        $ltlett = mb_substr($str, 1, 3, 'UTF-8'); // lấy 3 ký tự cuối cùng
        $llett = mb_substr($str, 3, 1, 'UTF-8'); // lấy 1 ký tự cuối cùng

        // nguyên âm đầu 1 ký tự, phụ âm cuối 3 ký tự // không có trường hợp này
        // vì không có phụ âm cuối 3 ký tự

        // nguyên âm đầu 2 ký tự, phụ âm cuối 2 ký tự
        // nguyên âm 2 ký tự vna_diphthongs() && phụ âm cuối 2 ký tự vna_last_csnt2() && không thuộc vna_no_sound_end2() các là các nguyên âm đôi không được phép có ký tự cuối
        if (in_array($fslett, $this->vna_diphthongs()) && in_array($lslett, $this->vna_last_csnt2()) && !in_array($fslett, $this->vna_no_sound_end2())) {
            $rs = 1;
        }

        // nguyên âm đầu 3 ký tự, phụ âm cuối 1 ký tự
        // nguyên âm 3 ký tự vna_triphthongs() && phụ âm cuối 1 ký tự vna_last_csnt1() && không thuộc mảng nguyên âm 3 không được phép có ký tự ở cuối 
        if (in_array($ftlett, $this->vna_triphthongs()) && in_array($llett, $this->vna_last_csnt1()) && !in_array($ftlett, $this->vna_no_sound_end3())) {
            $rs = 1;
        }

        //phụ âm đầu 1 ký tự, nguyên âm giữa 2 ký tự, phụ âm cuối 1 ký tự
        // vna_first_csnt1() phụ âm đầu 1 ký tự hợp lệ && vna_diphthongs() nguyên âm 2 ký tự && không thuộc vna_no_sound_end2() mảng nguyên âm đôi không được phép có ký tự cuối
        // vna_last_csnt1() mảng phụ âm cuối 1 ký tự hợp lệ
        if (in_array($flett, $this->vna_first_csnt1()) && in_array($stlett, $this->vna_diphthongs()) && !in_array($stlett, $this->vna_no_sound_end2()) && in_array($llett, $this->vna_last_csnt1())) {
            $rs = 1;
        }

        // phụ âm đầu 1 ký tự, nguyên âm cuối 3 ký tự
        // vna_first_csnt1() phụ âm đầu 1 ký tự hợp lệ && vna_triphthongs() mảng nguyên âm 3 ký tự  && khác nguyên âm ba cần phụ âm cuối
        if (in_array($flett, $this->vna_first_csnt1()) && in_array($ltlett, $this->vna_triphthongs()) && $ltlett != "uyê") {
            $rs = 1;
        }

        // phụ âm đầu 1 ký tự, nguyên âm giữa 1 ký tự, phụ âm cuối 2 ký tự
        // vna_first_csnt1() phụ âm 1 kt hợp lệ && vna_vowel_lett() mảng nguyên âm 1 kt && vna_last_csnt2 phụ âm cuối 2 ký tự hợp lệ
        if (in_array($flett, $this->vna_first_csnt1()) && in_array($slett, $this->vna_vowel_lett()) && in_array($lslett, $this->vna_last_csnt2())) {
            $rs = 1;
        }

        //phụ âm đầu 2 ký tự, nguyên âm cuối 2 ký tự
        // vna_first_csnt2() phụ âm đầu 2 ký tự hợp lệ && vna_diphthongs() nguyên âm đôi, 
        // vna_final_csnt_req2() và vna_final_voc_req2() là mảng các nguyên âm đôi cần âm cuối
        if (in_array($fslett, $this->vna_first_csnt2()) && in_array($lslett, $this->vna_diphthongs()) && !in_array($str, $this->vna_final_csnt_req2()) && !in_array($str, $this->vna_final_voc_req2())) {
            $rs = 1;
        }

        // phụ âm đầu 2 ký tự, nguyên âm giữa 1 ký tự, phụ âm cuối 1 ký tự
        // vna_first_csnt2() mảng phụ âm đôi hợp lệ && vna_vowel_lett() mảng nguyên âm 1 ký tự && vna_last_csnt1() phụ âm cuối 1 ký tự 
        if (in_array($fslett, $this->vna_first_csnt2()) && in_array($tlett, $this->vna_vowel_lett()) && in_array($llett, $this->vna_last_csnt1())) {
            $rs = 1;
        }

        //phụ âm đầu 3 ký tự, nguyên âm cuối 1 ký tự
        // vna_first_csnt3() phụ âm đầu 3 ký tự hợp lệ && vna_vowel_lett() nguyên âm một ký tự
        // phụ âm đầu 3 ký tự chỉ có trường hợp ngh
        // nguyên âm đơn cuối cần khác ă và â vì trường hợp này cần âm cuối
        if ($ftlett == "ngh" && in_array($llett, $this->vna_vowel_lett()) && $llett != "ă" && $llett != "â") {
            $rs = 1;
        }

        return $rs;
    }


    //==============================================================================
    function vn_spell_five_chars($str)
    { // kiểm tra từ có 5 ký tự
        $rs = 0;
        $lett01 = mb_substr($str, 0, 1, 'UTF-8'); // lấy 1 ký tự đầu tiên
        $lett02 = mb_substr($str, 0, 2, 'UTF-8'); // lấy 2 ký tự đầu tiên

        $lett12 = mb_substr($str, 1, 2, 'UTF-8'); // lấy 2 ký tự sau ký tự đầu tiên
        $lett13 = mb_substr($str, 1, 3, 'UTF-8'); // lấy 3 ký tự sau ký tự đầu tiên
        $lett22 = mb_substr($str, 2, 2, 'UTF-8'); // lấy 2 ký tự sau 2 ký tự đầu tiên    

        $lett03 = mb_substr($str, 0, 3, 'UTF-8'); // lấy 3 ký tự đầu tiên

        $lett21 = mb_substr($str, 2, 1, 'UTF-8'); // lấy 1 ký tự thứ ba  

        $lett41 = mb_substr($str, 4, 1, 'UTF-8'); // lấy 1 ký tự cuối cùng
        $lett31 = mb_substr($str, 3, 1, 'UTF-8'); // lấy 1 ký tự ngay trước ký tự cuối cùng
        $lett32 = mb_substr($str, 3, 2, 'UTF-8'); // lấy 2 ký tự cuối cùng
        $lett23 = mb_substr($str, 2, 3, 'UTF-8'); // lấy 3 ký tự cuối cùng

        // 1 nguyên âm đầu, 4 phụ âm cuối, không có trường hợp này------------------
        // 1 nguyên âm đầu, 3 phụ âm cuối, 1 nguyên âm cuối không có trường hợp này----
        // nguyên âm đầu 2 ký tự, phụ âm cuối 3 ký tự, không có trường hợp này------

        // nguyên âm đầu 3 ký tự, phụ âm cuối 2 ký tự
        // vna_triphthongs() mảng nguyên âm 3 ký tự, vna_last_csnt2() mảng phụ âm cuối 2 ký tự, vna_no_sound_end3() mảng các nguyên âm 3 không được phép có âm cuối 
        if (in_array($lett03, $this->vna_triphthongs()) && in_array($lett32, $this->vna_last_csnt2()) && !in_array($lett03, $this->vna_no_sound_end3())) {
            $rs = 1;
        }

        // nguyên âm đầu 4 ký tự, phụ âm cuối 1 ký tự, không có trường hợp này------
        // nguyên âm đầu 5 ký tự, không có trường hợp này---------------------------
        // phụ âm đầu 1 ký tự, nguyên âm cuối 4 ký tự, không có trường hợp này------

        // phụ âm đầu 1 ký tự, nguyên âm tiếp theo 3 ký tự, phụ âm cuối 1 ký tự
        // vna_first_csnt1() mảng phụ âm đầu 1 ký tự, vna_triphthongs() mảng nguyên âm 3 ký tự, vna_last_csnt1() mảng phụ âm cuối 1 ký tự
        // vna_no_sound_end3() mảng nguyên âm 3 không được phép có âm cuối
        if (in_array($lett01, $this->vna_first_csnt1()) && in_array($lett13, $this->vna_triphthongs()) && in_array($lett41, $this->vna_last_csnt1()) && !in_array($lett13, $this->vna_no_sound_end3())) {
            $rs = 1;
        }

        // phụ âm đầu 1 ký tự, nguyên âm tiếp theo 2 ký tự, phụ âm cuối 2 ký tự 
        // vna_first_csnt1() mảng phụ âm đầu 1 ký tự, vna_diphthongs() mảng nguyên âm 2 ký tự, vna_last_csnt2() mảng phụ âm cuối 2 ký tự
        // vna_no_sound_end2() mảng nguyên âm 2 ký tự không được phép có âm cuối
        if (in_array($lett01, $this->vna_first_csnt1()) && in_array($lett12, $this->vna_diphthongs()) && in_array($lett32, $this->vna_last_csnt2()) && !in_array($lett12, $this->vna_no_sound_end2())) {
            $rs = 1;
        }

        // phụ âm đầu 1 ký tự, nguyên âm tiếp theo 1 ký tự, phụ âm cuối 3 ký tự, trường hợp này không tồn tại------------

        // phụ âm đầu 2 ký tự, nguyên âm tiếp theo 3 ký tự
        // vna_first_csnt2() mảng phụ âm đầu 2 ký tự, vna_triphthongs() mảng nguyên âm 3 ký tự, khác uyê vì đây là nguyên âm 3 cần phụ âm cuối
        if (in_array($lett02, $this->vna_first_csnt2()) && in_array($lett23, $this->vna_triphthongs()) && $lett23 != "uyê") {
            $rs = 1;
        }

        // phụ âm đầu 2 ký tự, nguyên âm tiếp theo 2 ký tự, phụ âm cuối 1 ký tự
        // vna_first_csnt2() mảng phụ âm đầu 2 ký tự, vna_diphthongs() mảng nguyên âm 2 ký tự, vna_last_csnt1() phụ âm cuối 1 ký tự
        // vna_no_sound_end2() mảng nguyên âm không được phép có âm cuối
        if (in_array($lett02, $this->vna_first_csnt2()) && in_array($lett22, $this->vna_diphthongs()) && in_array($lett41, $this->vna_last_csnt1()) && !in_array($lett22, $this->vna_no_sound_end2())) {
            $rs = 1;
        }

        // phụ âm đầu 2 ký tự, nguyên âm tiếp theo 1 ký tự, phụ âm cuối 2 ký tự
        // vna_first_csnt2() phụ âm đầu 2 ký tự, vna_vowel_lett() nguyên âm 1 ký tự, vna_last_csnt2() phụ âm cuối 2 ký tự 
        if (in_array($lett02, $this->vna_first_csnt2()) && in_array($lett21, $this->vna_vowel_lett()) && in_array($lett32, $this->vna_last_csnt2())) {
            $rs = 1;
        }

        // phụ âm đầu 3 ký tự, nguyên âm tiếp theo 2 ký tự
        // vna_first_csnt3() phụ âm đầu 3 ký tự (chỉ có mỗi ngh), vna_diphthongs() nguyên âm 2 ký tự
        // vna_final_csnt_req2() và vna_final_voc_req2() là các nguyên âm đôi cần âm cuối
        if ($lett03 == "ngh" && in_array($lett32, $this->vna_diphthongs()) && !in_array($lett32, $this->vna_final_csnt_req2()) && !in_array($lett32, $this->vna_final_voc_req2())) {
            $rs = 1;
        }

        // phụ âm đầu 3 ký tự, nguyên âm tiếp theo 1 ký tự, phụ âm cuối 1 ký tự
        // phụ âm đầu 3 ký tự chỉ có mỗi trường hợp ngh, vna_vowel_lett() nguyên âm một ký tự
        // vna_last_csnt1() phụ âm cuối 1 ký tự
        if ($lett03 == "ngh" && in_array($lett31, $this->vna_vowel_lett()) && in_array($lett41, $this->vna_last_csnt1())) {
            $rs = 1;
        }

        return $rs;
    }


    //==============================================================================
    function vn_spell_six_chars($str)
    { // kiểm tra từ có 6 ký tự
        $rs = 0;

        $lett01 = mb_substr($str, 0, 1, 'UTF-8'); // lấy 1 ký tự đầu tiên
        $lett02 = mb_substr($str, 0, 2, 'UTF-8'); // lấy 2 ký tự đầu tiên

        $lett13 = mb_substr($str, 1, 3, 'UTF-8'); // lấy 3 ký tự sau ký tự đầu tiên
        $lett22 = mb_substr($str, 2, 2, 'UTF-8'); // lấy 2 ký tự sau 2 ký tự đầu tiên
        $lett32 = mb_substr($str, 3, 2, 'UTF-8'); // lấy 2 ký tự sau 3 ký tự đầu tiên    
        $lett23 = mb_substr($str, 2, 3, 'UTF-8'); // lấy 3 ký tự sau 2 ký tự đầu tiên 

        $lett03 = mb_substr($str, 0, 3, 'UTF-8'); // lấy 3 ký tự đầu tiên
        $lett31 = mb_substr($str, 3, 1, 'UTF-8'); // lấy 1 ký tự sau 3 ký tự đầu

        $lett51 = mb_substr($str, 5, 1, 'UTF-8'); // lấy 1 ký tự cuối cùng
        $lett42 = mb_substr($str, 4, 2, 'UTF-8'); // lấy 2 ký tự cuối cùng
        $lett33 = mb_substr($str, 3, 3, 'UTF-8'); // lấy 3 ký tự cuối cùng

        // nguyên âm đầu 1 ký tự, phụ âm cuối 5 ký tự, không có trường hợp này------------------
        // nguyên âm đầu 1 ký tự, phụ âm cuối 4 ký tự, nguyên âm cuối 1 ký tự, không có trường hợp này-----
        // nguyên âm đầu 2 ký tự, phụ âm cuối 4 ký tự, không có trường hợp này------
        // nguyên âm đầu 3 ký tự, phụ âm cuối 3 ký tự, không có trường hợp này------
        // nguyên âm đầu 5 ký tự, phụ âm cuối 1 ký tự, không có trường hợp này------
        // nguyên âm đầu 6 ký tự, không có trường hợp này---------------------------

        // phụ âm đầu 1 ký tự, nguyên âm cuối 5 ký tự, không có trường hợp này------

        // phụ âm đầu 1 ký tự, nguyên âm tiếp theo 3 ký tự, phụ âm cuối 2 ký tự
        // vna_first_csnt1() phụ âm đầu 1 ký tự, vna_triphthongs() nguyên âm 3 ký tự, vna_last_csnt2() phụ âm cuối 2 ký tự
        // vna_no_sound_end3() nguyên âm 3 ký tự không được phép có âm cuối
        if (in_array($lett01, $this->vna_first_csnt1()) && in_array($lett13, $this->vna_triphthongs()) && in_array($lett42, $this->vna_last_csnt2()) && !in_array($lett13, $this->vna_no_sound_end3())) {
            $rs = 1;
        }
        // phụ âm đầu 1 ký tự, nguyên âm tiếp theo 2 ký tự, phụ âm cuối 3 ký tự, trường hợp này không tồn tại----- 
        // phụ âm đầu 1 ký tự, nguyên âm tiếp theo 1 ký tự, phụ âm cuối 4 ký tự, không có trường hợp này----

        // phụ âm đầu 2 ký tự, nguyên âm tiếp theo 4 ký tự, không có trường hợp này--------

        // phụ âm đầu 2 ký tự, nguyên âm tiếp theo 3 ký tự, phụ âm cuối 1 ký tự
        // vna_first_csnt2() phụ âm 2 ký tự, vna_triphthongs() nguyên âm 3 ký tự, vna_last_csnt1() phụ âm cuối 1 ký tự
        // vna_no_sound_end3() mảng nguyên âm 3 không được phép có ký tự ở cuối
        if (in_array($lett02, $this->vna_first_csnt2()) && in_array($lett23, $this->vna_triphthongs()) && in_array($lett51, $this->vna_last_csnt1()) && !in_array($lett23, $this->vna_no_sound_end3())) {
            $rs = 1;
        }

        // phụ âm đầu 2 ký tự, nguyên âm tiếp theo 2 ký tự, phụ âm cuối 2 ký tự
        // vna_first_csnt2() phụ âm đầu 2 ký tự, vna_diphthongs() nguyên âm 2 ký tự, vna_last_csnt2() phụ âm cuối 2 ký tự
        // vna_no_sound_end2() mảng nguyên âm đôi không được phép có âm cuối
        if (in_array($lett02, $this->vna_first_csnt2()) && in_array($lett22, $this->vna_diphthongs()) && in_array($lett42, $this->vna_last_csnt2()) && !in_array($lett22, $this->vna_no_sound_end2())) {
            $rs = 1;
        }

        // phụ âm đầu 2 ký tự, nguyên âm tiếp theo 1 ký tự, phụ âm cuối 3 ký tự, không có trường hợp này----

        // phụ âm đầu 3 ký tự, nguyên âm tiếp theo 3 ký tự
        // phụ âm đầu 3 ký tự chỉ có ngh, vna_triphthongs() mảng nguyên âm 3 ký tự
        if ($lett03 == "ngh" && in_array($lett33, $this->vna_triphthongs())) {
            $rs = 1;
        }

        // phụ âm đầu 3 ký tự, nguyên âm tiếp theo 2 ký tự, phụ âm cuối 1 ký tự
        // vna_diphthongs() mảng nguyên âm 2 ký tự, vna_last_csnt1() phụ âm cuối 1 ký tự
        // vna_no_sound_end2() mảng các nguyên âm đôi không được có âm cuối
        if ($lett03 == "ngh" && in_array($lett32, $this->vna_diphthongs()) && in_array($lett51, $this->vna_last_csnt1()) && !in_array($lett22, $this->vna_no_sound_end2())) {
            $rs = 1;
        }

        // phụ âm đầu 3 ký tự, nguyên âm tiếp theo 1 ký tự, phụ âm cuối 2 ký tự
        // vna_vowel_lett() mảng nguyên âm một ký tự, vna_last_csnt2() mảng phụ âm 2 ký tự
        if ($lett03 == "ngh" && in_array($lett31, $this->vna_vowel_lett()) && in_array($lett42, $this->vna_last_csnt2())) {
            $rs = 1;
        }

        return $rs;
    }


    // chỉ áp dụng với một từ, kiểm tra chính tả
    function vn_spell_chr_small($str)
    {
        $rs = 1; // ban đầu cho là đúng chính tả
        $str2 = $this->vn_low_rmv($str); // xóa khoảng trắng dư thừa, chuyển thành ký tự thường
        $str3 = $this->vn_remove_accents($str); // xóa dấu
        $count_char = $this->vn_num_char($str3); // số lượng ký tự

        // không được có ký tự nước ngoài
        if ($this->vn_foreign_check_low($str2)) {
            $rs = 0;
        }

        // từ tiếng Việt có nhiều chữ cái nhất là nghiêng với 7 chữ cái
        // các từ đúng chính tả chỉ có 6 chữ cái trở xuống
        if ($rs == 1) { // để nó đỡ phải thực hiện kiểm tra quá nhiều
            if (($str2 != "nghiêng") && ($this->vn_num_char($str2) > 6)) {
                $rs = 0;
            }
        }

        // số lượng từ có dấu không được lớn hơn 1
        if ($rs == 1) { // để nó đỡ phải thực hiện kiểm tra quá nhiều
            if ($this->vn_num_acc_char($str2) > 1) {
                $rs = 0;
            }
        }

        // tối đa 3 nguyên âm, tối thiểu 1 nguyên âm và các nguyên âm cần phải đứng cạnh nhau
        if ($rs == 1) { // để nó đỡ phải thực hiện kiểm tra quá nhiều
            if ($this->vn_vowel_next_other($str2) == 0) {
                $rs = 0;
            }
        }

        // nếu từ có một ký tự
        if ($count_char == 1) {
            if ($this->vn_spell_one_char($str3) == 0) {
                $rs = 0;
            }
        }

        // nếu từ có hai ký tự
        if ($count_char == 2) {
            if ($this->vn_spell_two_chars($str3) == 0) {
                $rs = 0;
            }
        }

        // nếu từ có ba ký tự
        if ($count_char == 3) {
            if ($this->vn_spell_three_chars($str3) == 0) {
                $rs = 0;
            }
        }

        // nếu từ có bốn ký tự
        if ($count_char == 4) {
            if ($this->vn_spell_four_chars($str3) == 0) {
                $rs = 0;
            }
        }

        // nếu từ có năm ký tự
        if ($count_char == 5) {
            if ($this->vn_spell_five_chars($str3) == 0) {
                $rs = 0;
            }
        }

        // nếu từ có sáu ký tự
        if ($count_char == 6) {
            if ($this->vn_spell_six_chars($str3) == 0) {
                $rs = 0;
            }
        }

        return $rs;
    }


    // thiết kế kiểm tra chính tả cho một cụm nhiều từ
    function vn_spell_chr_big($str)
    {
        $rs = 1; // gán cho đúng chính tả lúc ban đầu 
        $str2 = $this->vn_rmv_wsp($str); // xóa bỏ khoảng trắng dư thừa
        $words = mb_split(' ', $str2); // tách từ

        foreach ($words as $word) {
            if ($word != NULL) {
                if ($this->vn_spell_chr_small($word) == 0) {
                    $rs = 0;
                    break;
                }
            }
        }

        return $rs;
    }


    // lett viết tắt của letters nghĩa là các chữ cái
    function vna_all_lett()
    { // mảng chữ cái tiếng Việt, gồm 29 chữ cái
        $letters = array("a", "ă", "â", "b", "c", "d", "đ", "e", "ê", "g", "h", "i", "k", "l", "m", "n", "o", "ô", "ơ", "p", "q", "r", "s", "t", "u", "ư", "v", "x", "y");

        return $letters;
    }



    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////



    // mã hóa hex của dấu tiếng Việt, thanh bằng không có dấu
    function vna_hex_timbre()
    {
        $timbre = array("cc81", "cc80", "cc89", "cc83", "cca3"); // sắc, huyền, hỏi, ngã, nặng

        return $timbre;
    }

    // cc81: sắc

    // cc80: huyền

    // cc89: hỏi

    // cc83: ngã

    // cca3: nặng



    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////



    // vowel nghĩa là nguyên âm
    function vna_vowel_lett()
    { // mảng nguyên âm đơn tiếng Việt, mã hóa phổ thông, không kèm dấu, 12 nguyên âm đơn
        $sv = array("a", "ă", "â", "e", "ê", "i", "o", "ô", "ơ", "u", "ư", "y");

        return $sv;
    }



    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////



    // diphthongs nghĩa là nguyên âm đôi
    function vna_diphthongs()
    { //nguyên âm đôi tiếng Việt, không kèm dấu, 32 nguyên âm đôi
        $diphthongs = array("ai", "ao", "au", "âu", "ay", "ây", "eo", "êu", "ia", "iê", "yê", "iu", "oa", "oă", "oe", "oi", "ôi", "ơi", "oo", "ôô", "ua", "uă", "uâ", "ưa", "uê", "ui", "ưi", "uo", "uô", "uơ", "ươ", "ưu", "uy");

        return $diphthongs;
    }



    ////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////



    // triphthongs nghĩa là nguyên âm ba
    function vna_triphthongs()
    { //nguyên âm ba tiếng Việt, không kèm dấu, 14 nguyên âm ba
        $triphthongs = array("iêu", "yêu", "oai", "oao", "oay", "oeo", "uây", "uôi", "ươi", "ươu", "uya", "uyu", "uyê", "uao");

        return $triphthongs;
    }



    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////



    // 26 phụ âm được phép đứng đầu từ
    function vna_first_csnt()
    { // phụ âm đầu được phép trong tiếng Việt, gồm 26 phụ âm đầu
        $vfc = array("b", "c", "ch", "d", "đ", "g", "gh", "gi", "h", "k", "kh", "l", "m", "n", "nh", "ng", "ngh", "ph", "qu", "r", "s", "t", "th", "tr", "v", "x");

        return $vfc;
    }

    // 15 phụ âm đơn được phép đứng đầu từ
    function vna_first_csnt1()
    {
        $vfc = array("b", "c", "d", "đ", "g", "h", "k", "l", "m", "n", "r", "s", "t", "v", "x");

        return $vfc;
    }

    // 10 phụ âm đôi được phép đứng đầu từ
    function vna_first_csnt2()
    {
        $vfc = array("ch", "gh", "gi", "kh", "nh", "ng", "ph", "qu", "th", "tr");

        return $vfc;
    }

    // 1 phụ âm ba được phép đứng đầu từ
    function vna_first_csnt3()
    {
        $vfc = array("ngh");

        return $vfc;
    }

    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////



    // 8 phụ âm được phép đứng cuối từ, không có phụ âm cuối nào có hơn 2 ký tự
    function vna_last_csnt()
    { // phụ âm cuối được phép trong tiếng Việt, gồm 8 phụ âm, mã hóa hex phổ thông
        $vlc = array("c", "ch", "m", "n", "nh", "ng", "p", "t");

        return $vlc;
    }

    // 5 phụ âm đơn được phép đứng cuối từ
    function vna_last_csnt1()
    {
        $vlc = array("c", "m", "n", "p", "t");

        return $vlc;
    }

    // 3 phụ âm đôi được phép đứng cuối từ
    function vna_last_csnt2()
    {
        $vlc = array("ch", "nh", "ng");

        return $vlc;
    }


    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////



    // 6 nguyên âm bắt bắt buộc phải có phụ âm cuối
    function vna_final_csnt_req()
    { // những nguyên âm bắt buộc phải có phụ âm cuối, không được phép là nguyên âm
        $fcr = array("ă", "oă", "oo", "ôô", "uă", "uyê");

        return $fcr;
    }

    // 1 nguyên âm đơn bắt bắt buộc phải có phụ âm cuối
    function vna_final_csnt_req1()
    {
        $fcr = array("ă");

        return $fcr;
    }

    // 4 nguyên âm đôi bắt bắt buộc phải có phụ âm cuối
    function vna_final_csnt_req2()
    {
        $fcr = array("oă", "oo", "ôô", "uă");

        return $fcr;
    }

    // 1 nguyên âm ba bắt bắt buộc phải có phụ âm cuối
    function vna_final_csnt_req3()
    {
        $fcr = array("uyê");

        return $fcr;
    }


    ////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////



    // 6 nguyên âm bắt buộc phải có âm cuối
    function vna_final_voc_req()
    { // những nguyên âm cần có âm cuối và có thể là nguyên âm hoặc phụ âm đều được
        $fvr = array("â", "iê", "uâ", "uô", "ươ", "yê");

        return $fvr;
    }

    // 1 nguyên âm đơn bắt buộc phải có âm cuối
    function vna_final_voc_req1()
    {
        $fvr = array("â");

        return $fvr;
    }

    // 5 nguyên âm đôi bắt buộc phải có âm cuối
    function vna_final_voc_req2()
    {
        $fvr = array("iê", "uâ", "uô", "ươ", "yê");

        return $fvr;
    }

    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////



    // 29 nguyên âm đôi và ba không được phép có âm cuối
    // gồm 16 nguyên âm đôi và 13 nguyên âm ba
    function vna_no_sound_end()
    { // những nguyên âm không được phép có âm cuối, dù âm cuối là nguyên âm hay phụ âm
        $nse = array("ai", "ao", "au", "âu", "eo", "êu", "ia", "iu", "oi", "ôi", "ơi", "ưa", "ui", "ưi", "ưu", "uơ", "iêu", "yêu", "oai", "oao", "oay", "oeo", "uai", "uây", "uôi", "ươi", "ươu", "uya", "uyu");

        return $nse;
    }

    function vna_no_sound_end2()
    { // 16 nguyên âm đôi không được phép có âm cuối, dù âm cuối là nguyên âm hay phụ âm
        $nse = array("ai", "ao", "au", "âu", "eo", "êu", "ia", "iu", "oi", "ôi", "ơi", "ưa", "ui", "ưi", "ưu", "uơ");

        return $nse;
    }

    function vna_no_sound_end3()
    { // 13 nguyên âm ba không được phép có âm cuối, dù âm cuối là nguyên âm hay phụ âm
        $nse = array("iêu", "yêu", "oai", "oao", "oay", "oeo", "uai", "uây", "uôi", "ươi", "ươu", "uya", "uyu");

        return $nse;
    }

    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////



    // 60 nguyên âm có dấu đi kèm
    function vna_acc_char_array()
    { // mảng nguyên âm đơn có dấu, mã hóa hex phổ thông, gồm 60 ký tự
        $acc = array("á", "à", "ả", "ã", "ạ", "ắ", "ằ", "ẳ", "ẵ", "ặ", "ấ", "ầ", "ẩ", "ẫ", "ậ", "é", "è", "ẻ", "ẽ", "ẹ", "ế", "ề", "ể", "ễ", "ệ", "ó", "ò", "ỏ", "õ", "ọ", "ố", "ồ", "ổ", "ỗ", "ộ", "ờ", "ớ", "ở", "ỡ", "ợ", "ú", "ù", "ủ", "ũ", "ụ", "ứ", "ừ", "ử", "ữ", "ự", "ý", "ỳ", "ỷ", "ỹ", "ỵ", "í", "ì", "ỉ", "ĩ", "ị");

        return $acc;
    }



    ////////////////////////////////////////////////////////////////////////////////
    // các ký tự viết HOA từ tiếng Việt, bao gồm cả có dấu và không dấu
    function vn_upp_letters()
    {
        $upp = array("A", "Á", "À", "Ả", "Ã", "Ạ", "Ă", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ", "Â", "Ấ", "Ầ", "Ẩ", "Ẫ", "Ậ", "E", "É", "È", "Ẻ", "Ẽ", "Ẹ", "Ê", "Ế", "Ề", "Ể", "Ễ", "Ệ", "O", "Ó", "Ò", "Ỏ", "Õ", "Ọ", "Ô", "Ố", "Ồ", "Ổ", "Ỗ", "Ộ", "Ơ", "Ờ", "Ớ", "Ở", "Ỡ", "Ợ", "U", "Ú", "Ù", "Ủ", "Ũ", "Ụ", "Ư", "Ứ", "Ừ", "Ử", "Ữ", "Ự", "Y", "Ý", "Ỳ", "Ỷ", "Ỹ", "Ỵ", "I", "Í", "Ì", "Ỉ", "Ĩ", "Ị", "Đ", "B", "C", "D", "G", "H", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "V", "X");

        return $upp;
    }

    function pop_hex_convert($strx)
    { // chuyển từ mã hóa hex ít phổ biến sang mã hóa phổ biến hơn dành cho ký tự thường
        $str2 = trim($strx, ' '); // bỏ khoảng trắng trước và sau chuỗi
        $str3 = preg_replace('/\s+/', ' ', $str2); // loại bỏ khoảng trắng thừa trong chuỗi, chỉ giữ lại một khoảng trắng giữa các từ
        $str = mb_strtolower($str3, 'UTF-8'); // chuyển tất cả thành ký tự thường

        $phothong = array();  // tạo mảng chữ cái mã hóa phổ biến
        $itdung = array();  //tạo mảng chữ cái mã hóa ít dùng

        // Vần y

        $phothong[0] = 'ỵ';
        $itdung[0] = 'ỵ';

        // Vần a thường xong

        $phothong[1] = 'á';
        $itdung[1] = 'á';

        $phothong[2] = 'à';
        $itdung[2] = 'à';

        $phothong[3] = 'ả';
        $itdung[3] = 'ả';

        $phothong[4] = 'ã';
        $itdung[4] = 'ã';

        $phothong[5] = 'ạ';
        $itdung[5] = 'ạ';

        // ///////////////////////////

        // Vần ă thường xong

        $phothong[6] = 'ắ';
        $itdung[6] = 'ắ';

        $phothong[7] = 'ằ';
        $itdung[7] = 'ằ';

        $phothong[8] = 'ẳ';
        $itdung[8] = 'ẳ';

        $phothong[9] = 'ẵ';
        $itdung[9] = 'ẵ';

        $phothong[10] = 'ặ';
        $itdung[10] = 'ặ';

        /////////////////////////////

        // Vần â thường xong

        $phothong[11] = 'ấ';
        $itdung[12] = 'ấ';

        $phothong[12] = 'ầ';
        $itdung[11] = 'ầ';

        $phothong[13] = 'ậ';
        $itdung[13] = 'ậ';

        $phothong[14] = 'ẩ';
        $itdung[14] = 'ẩ';

        $phothong[15] = 'ẫ';
        $itdung[15] = 'ẫ';


        /////////////////////

        // Vần e thường xong

        $phothong[16] = 'é';
        $itdung[16] = 'é';

        $phothong[17] = 'è';
        $itdung[17] = 'è';

        $phothong[18] = 'ẻ';
        $itdung[18] = 'ẻ';

        $phothong[19] = 'ẽ';
        $itdung[19] = 'ẽ';

        $phothong[20] = 'ẹ';
        $itdung[20] = 'ẹ';


        // ////////////////////////

        // Vần ê thường xong

        $phothong[21] = 'ế';
        $itdung[21] = 'ế';

        $phothong[22] = 'ề';
        $itdung[22] = 'ề';

        $phothong[23] = 'ể';
        $itdung[23] = 'ể';

        $phothong[24] = 'ễ';
        $itdung[24] = 'ễ';

        $phothong[25] = 'ệ';
        $itdung[25] = 'ệ';


        // //////////////////////

        // Vần o thường xong

        $phothong[26] = 'ó';
        $itdung[26] = 'ó';

        $phothong[27] = 'ò';
        $itdung[27] = 'ò';

        $phothong[28] = 'ỏ';
        $itdung[28] = 'ỏ';

        $phothong[29] = 'õ';
        $itdung[29] = 'õ';

        $phothong[30] = 'ọ';
        $itdung[30] = 'ọ';

        // ////////////////

        // Vần ô thường xong

        $phothong[31] = 'ố';
        $itdung[31] = 'ố';

        $phothong[32] = 'ồ';
        $itdung[32] = 'ồ';

        $phothong[33] = 'ổ';
        $itdung[33] = 'ổ';

        $phothong[34] = 'ỗ';
        $itdung[34] = 'ỗ';

        $phothong[35] = 'ộ';
        $itdung[35] = 'ộ';

        // //////////////////////

        // Vần ơ thường xong

        $phothong[36] = 'ớ';
        $itdung[36] = 'ớ';

        $phothong[37] = 'ờ';
        $itdung[37] = 'ờ';

        $phothong[38] = 'ở';
        $itdung[38] = 'ở';

        $phothong[39] = 'ỡ';
        $itdung[39] = 'ỡ';

        $phothong[40] = 'ợ';
        $itdung[40] = 'ợ';

        // ////////////////////

        // Vần i thường xong

        $phothong[41] = 'í';
        $itdung[41] = 'í';

        $phothong[42] = 'ì';
        $itdung[42] = 'ì';

        $phothong[43] = 'ỉ';
        $itdung[43] = 'ỉ';

        $phothong[44] = 'ĩ';
        $itdung[44] = 'ĩ';

        $phothong[45] = 'ị';
        $itdung[45] = 'ị';

        // // //////////////////

        // Vần u thường xong

        $phothong[46] = 'ú';
        $itdung[46] = 'ú';

        $phothong[47] = 'ù';
        $itdung[47] = 'ù';

        $phothong[48] = 'ủ';
        $itdung[48] = 'ủ';

        $phothong[49] = 'ũ';
        $itdung[49] = 'ũ';

        $phothong[50] = 'ụ';
        $itdung[50] = 'ụ';

        // ///////////////

        // Vần ư thường xong

        $phothong[51] = 'ứ';
        $itdung[51] = 'ứ';

        $phothong[52] = 'ừ';
        $itdung[52] = 'ừ';

        $phothong[53] = 'ử';
        $itdung[53] = 'ử';

        $phothong[54] = 'ữ';
        $itdung[54] = 'ữ';

        $phothong[55] = 'ự';
        $itdung[55] = 'ự';

        // ////////////////////

        // Vần y thường xong

        $phothong[56] = 'ý';
        $itdung[56] = 'ý';

        $phothong[57] = 'ỳ';
        $itdung[57] = 'ỳ';

        $phothong[58] = 'ỷ';
        $itdung[58] = 'ỷ';

        $phothong[59] = 'ỹ';
        $itdung[59] = 'ỹ';

        // ////////////////////

        for ($j = 0; $j < 60; $j++) { // chạy vòng lặp để chuyển ký tự
            $pattern = '/' . $itdung[$j] . '/';
            $str = preg_replace($pattern, $phothong[$j], $str);
        }

        return $str;
    }



    ////////////////////////////////////////////////////////////////////////////////

    /////////////


    function rarely_hex_convert($strx)
    { // chuyển mã hóa phổ biến về dạng không phổ biến, dành cho ký tự thường
        $str2 = trim($strx, ' '); // bỏ khoảng trắng trước và sau chuỗi
        $str3 = preg_replace('/\s+/', ' ', $str2); // loại bỏ khoảng trắng thừa trong chuỗi
        $str = mb_strtolower($str3, 'UTF-8'); // chuyển thành ký tự thường

        $phothong = array();  // tạo mảng chữ cái mã hóa phổ biến
        $itdung = array();  //tạo mảng chữ cái mã hóa ít dùng

        // Vần y

        $phothong[0] = 'ỵ';
        $itdung[0] = 'ỵ';

        // Vần a thường xong

        $phothong[1] = 'á';
        $itdung[1] = 'á';

        $phothong[2] = 'à';
        $itdung[2] = 'à';

        $phothong[3] = 'ả';
        $itdung[3] = 'ả';

        $phothong[4] = 'ã';
        $itdung[4] = 'ã';

        $phothong[5] = 'ạ';
        $itdung[5] = 'ạ';

        // ///////////////////////////

        // Vần ă thường xong

        $phothong[6] = 'ắ';
        $itdung[6] = 'ắ';

        $phothong[7] = 'ằ';
        $itdung[7] = 'ằ';

        $phothong[8] = 'ẳ';
        $itdung[8] = 'ẳ';

        $phothong[9] = 'ẵ';
        $itdung[9] = 'ẵ';

        $phothong[10] = 'ặ';
        $itdung[10] = 'ặ';

        /////////////////////////////

        // Vần â thường xong

        $phothong[11] = 'ấ';
        $itdung[12] = 'ấ';

        $phothong[12] = 'ầ';
        $itdung[11] = 'ầ';

        $phothong[13] = 'ậ';
        $itdung[13] = 'ậ';

        $phothong[14] = 'ẩ';
        $itdung[14] = 'ẩ';

        $phothong[15] = 'ẫ';
        $itdung[15] = 'ẫ';


        /////////////////////

        // Vần e thường xong

        $phothong[16] = 'é';
        $itdung[16] = 'é';

        $phothong[17] = 'è';
        $itdung[17] = 'è';

        $phothong[18] = 'ẻ';
        $itdung[18] = 'ẻ';

        $phothong[19] = 'ẽ';
        $itdung[19] = 'ẽ';

        $phothong[20] = 'ẹ';
        $itdung[20] = 'ẹ';


        // ////////////////////////

        // Vần ê thường xong

        $phothong[21] = 'ế';
        $itdung[21] = 'ế';

        $phothong[22] = 'ề';
        $itdung[22] = 'ề';

        $phothong[23] = 'ể';
        $itdung[23] = 'ể';

        $phothong[24] = 'ễ';
        $itdung[24] = 'ễ';

        $phothong[25] = 'ệ';
        $itdung[25] = 'ệ';


        // //////////////////////

        // Vần o thường xong

        $phothong[26] = 'ó';
        $itdung[26] = 'ó';

        $phothong[27] = 'ò';
        $itdung[27] = 'ò';

        $phothong[28] = 'ỏ';
        $itdung[28] = 'ỏ';

        $phothong[29] = 'õ';
        $itdung[29] = 'õ';

        $phothong[30] = 'ọ';
        $itdung[30] = 'ọ';

        // ////////////////

        // Vần ô thường xong

        $phothong[31] = 'ố';
        $itdung[31] = 'ố';

        $phothong[32] = 'ồ';
        $itdung[32] = 'ồ';

        $phothong[33] = 'ổ';
        $itdung[33] = 'ổ';

        $phothong[34] = 'ỗ';
        $itdung[34] = 'ỗ';

        $phothong[35] = 'ộ';
        $itdung[35] = 'ộ';

        // //////////////////////

        // Vần ơ thường xong

        $phothong[36] = 'ớ';
        $itdung[36] = 'ớ';

        $phothong[37] = 'ờ';
        $itdung[37] = 'ờ';

        $phothong[38] = 'ở';
        $itdung[38] = 'ở';

        $phothong[39] = 'ỡ';
        $itdung[39] = 'ỡ';

        $phothong[40] = 'ợ';
        $itdung[40] = 'ợ';

        // ////////////////////

        // Vần i thường xong

        $phothong[41] = 'í';
        $itdung[41] = 'í';

        $phothong[42] = 'ì';
        $itdung[42] = 'ì';

        $phothong[43] = 'ỉ';
        $itdung[43] = 'ỉ';

        $phothong[44] = 'ĩ';
        $itdung[44] = 'ĩ';

        $phothong[45] = 'ị';
        $itdung[45] = 'ị';

        // // //////////////////

        // Vần u thường xong

        $phothong[46] = 'ú';
        $itdung[46] = 'ú';

        $phothong[47] = 'ù';
        $itdung[47] = 'ù';

        $phothong[48] = 'ủ';
        $itdung[48] = 'ủ';

        $phothong[49] = 'ũ';
        $itdung[49] = 'ũ';

        $phothong[50] = 'ụ';
        $itdung[50] = 'ụ';

        // ///////////////

        // Vần ư thường xong

        $phothong[51] = 'ứ';
        $itdung[51] = 'ứ';

        $phothong[52] = 'ừ';
        $itdung[52] = 'ừ';

        $phothong[53] = 'ử';
        $itdung[53] = 'ử';

        $phothong[54] = 'ữ';
        $itdung[54] = 'ữ';

        $phothong[55] = 'ự';
        $itdung[55] = 'ự';

        // ////////////////////

        // Vần y thường xong

        $phothong[56] = 'ý';
        $itdung[56] = 'ý';

        $phothong[57] = 'ỳ';
        $itdung[57] = 'ỳ';

        $phothong[58] = 'ỷ';
        $itdung[58] = 'ỷ';

        $phothong[59] = 'ỹ';
        $itdung[59] = 'ỹ';

        // ////////////////////


        for ($j = 0; $j < 60; $j++) { // tiến hành chạy vòng lặp để thực hiện chuyển đổi
            $pattern = '/' . $phothong[$j] . '/';
            $str = preg_replace($pattern, $itdung[$j], $str);
        }

        return $str;
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    function pop_hex_upp_convert($strx)
    { // chuyển từ mã hóa ít phổ biến sang mã hóa phổ biến hơn dành cho từ có ký tự VIẾT HOA

        $str2 = trim($strx, ' '); // bỏ khoảng trắng trước và sau chuỗi
        $str = preg_replace('/\s+/', ' ', $str2); // loại bỏ khoảng trắng thừa trong chuỗi

        $phothong = array();  // tạo mảng chữ cái mã hóa phổ biến
        $itdung = array();  //tạo mảng chữ cái mã hóa ít dùng

        // Vần Y

        $phothong[0] = 'Ỵ';
        $itdung[0] = 'Ỵ';

        // Vần A xong

        $phothong[1] = 'Á';
        $itdung[1] = 'Á';

        $phothong[2] = 'À';
        $itdung[2] = 'À';

        $phothong[3] = 'Ả';
        $itdung[3] = 'Ả';

        $phothong[4] = 'Ã';
        $itdung[4] = 'Ã';

        $phothong[5] = 'Ạ';
        $itdung[5] = 'Ạ';

        // ///////////////////////////

        // /// Vần Ă xong

        $phothong[6] = 'Ắ';
        $itdung[6] = 'Ắ';

        $phothong[7] = 'Ằ';
        $itdung[7] = 'Ằ';

        $phothong[8] = 'Ẳ';
        $itdung[8] = 'Ẳ';

        $phothong[9] = 'Ẵ';
        $itdung[9] = 'Ẵ';

        $phothong[10] = 'Ặ';
        $itdung[10] = 'Ặ';

        /////////////////////////////

        // Vần Â xong

        $phothong[11] = 'Ấ';
        $itdung[12] = 'Ấ';

        $phothong[12] = 'Ầ';
        $itdung[11] = 'Ầ';

        $phothong[13] = 'Ậ';
        $itdung[13] = 'Ậ';

        $phothong[14] = 'Ẩ';
        $itdung[14] = 'Ẩ';

        $phothong[15] = 'Ẫ';
        $itdung[15] = 'Ẫ';


        /////////////////////

        // Vần E xong

        $phothong[16] = 'É';
        $itdung[16] = 'É';

        $phothong[17] = 'È';
        $itdung[17] = 'È';

        $phothong[18] = 'Ẻ';
        $itdung[18] = 'Ẻ';

        $phothong[19] = 'Ẽ';
        $itdung[19] = 'Ẽ';

        $phothong[20] = 'Ẹ';
        $itdung[20] = 'Ẹ';


        // ////////////////////////

        // Vần Ê xong

        $phothong[21] = 'Ế';
        $itdung[21] = 'Ế';

        $phothong[22] = 'Ề';
        $itdung[22] = 'Ề';

        $phothong[23] = 'Ể';
        $itdung[23] = 'Ể';

        $phothong[24] = 'Ễ';
        $itdung[24] = 'Ễ';

        $phothong[25] = 'Ệ';
        $itdung[25] = 'Ệ';


        // //////////////////////

        // Vần O xong

        $phothong[26] = 'Ó';
        $itdung[26] = 'Ó';

        $phothong[27] = 'Ò';
        $itdung[27] = 'Ò';

        $phothong[28] = 'Ỏ';
        $itdung[28] = 'Ỏ';

        $phothong[29] = 'Õ';
        $itdung[29] = 'Õ';

        $phothong[30] = 'Ọ';
        $itdung[30] = 'Ọ';

        // ////////////////

        // Vần Ô xong

        $phothong[31] = 'Ố';
        $itdung[31] = 'Ố';

        $phothong[32] = 'Ồ';
        $itdung[32] = 'Ồ';

        $phothong[33] = 'Ổ';
        $itdung[33] = 'Ổ';

        $phothong[34] = 'Ỗ';
        $itdung[34] = 'Ỗ';

        $phothong[35] = 'Ộ';
        $itdung[35] = 'Ộ';

        // //////////////////////

        // Vần Ơ xong

        $phothong[36] = 'Ớ';
        $itdung[36] = 'Ớ';

        $phothong[37] = 'Ờ';
        $itdung[37] = 'Ờ';

        $phothong[38] = 'Ở';
        $itdung[38] = 'Ở';

        $phothong[39] = 'Ỡ';
        $itdung[39] = 'Ỡ';

        $phothong[40] = 'Ợ';
        $itdung[40] = 'Ợ';

        // ////////////////////

        // Vần I xong

        $phothong[41] = 'Í';
        $itdung[41] = 'Í';

        $phothong[42] = 'Ì';
        $itdung[42] = 'Ì';

        $phothong[43] = 'Ỉ';
        $itdung[43] = 'Ỉ';

        $phothong[44] = 'Ĩ';
        $itdung[44] = 'Ĩ';

        $phothong[45] = 'Ị';
        $itdung[45] = 'Ị';

        // // //////////////////

        // Vần U xong

        $phothong[46] = 'Ú';
        $itdung[46] = 'Ú';

        $phothong[47] = 'Ù';
        $itdung[47] = 'Ù';

        $phothong[48] = 'Ủ';
        $itdung[48] = 'Ủ';

        $phothong[49] = 'Ũ';
        $itdung[49] = 'Ũ';

        $phothong[50] = 'Ụ';
        $itdung[50] = 'Ụ';

        // ///////////////

        // // Vần Ư xong

        $phothong[51] = 'Ứ';
        $itdung[51] = 'Ứ';

        $phothong[52] = 'Ừ';
        $itdung[52] = 'Ừ';

        $phothong[53] = 'Ử';
        $itdung[53] = 'Ử';

        $phothong[54] = 'Ữ';
        $itdung[54] = 'Ữ';

        $phothong[55] = 'Ự';
        $itdung[55] = 'Ự';

        // ////////////////////

        // Vần Y xong

        $phothong[56] = 'Ý';
        $itdung[56] = 'Ý';

        $phothong[57] = 'Ỳ';
        $itdung[57] = 'Ỳ';

        $phothong[58] = 'Ỷ';
        $itdung[58] = 'Ỷ';

        $phothong[59] = 'Ỹ';
        $itdung[59] = 'Ỹ';

        ///////////////////////

        for ($j = 0; $j < 60; $j++) { // thực hiện chạy vòng lặp để đổi
            $pattern = '/' . $itdung[$j] . '/';
            $str = preg_replace($pattern, $phothong[$j], $str);
        }

        return $str; // trả kết quả về
    }



    ////////////////////////////////////////////////////////////////////////////////



    function rarely_hex_upp_convert($strx)
    { // chuyển từ mã hóa phổ thông sang ít dùng dành cho từ có ký tự VIẾT HOA

        $str2 = trim($strx, ' '); // bỏ khoảng trắng trước và sau chuỗi
        $str = preg_replace('/\s+/', ' ', $str2); // loại bỏ khoảng trắng thừa trong chuỗi

        $phothong = array();  // tạo mảng chữ cái mã hóa phổ biến
        $itdung = array();  //tạo mảng chữ cái mã hóa ít dùng

        // Vần Y

        $phothong[0] = 'Ỵ';
        $itdung[0] = 'Ỵ';

        // Vần A xong

        $phothong[1] = 'Á';
        $itdung[1] = 'Á';

        $phothong[2] = 'À';
        $itdung[2] = 'À';

        $phothong[3] = 'Ả';
        $itdung[3] = 'Ả';

        $phothong[4] = 'Ã';
        $itdung[4] = 'Ã';

        $phothong[5] = 'Ạ';
        $itdung[5] = 'Ạ';

        // ///////////////////////////

        // /// Vần Ă xong

        $phothong[6] = 'Ắ';
        $itdung[6] = 'Ắ';

        $phothong[7] = 'Ằ';
        $itdung[7] = 'Ằ';

        $phothong[8] = 'Ẳ';
        $itdung[8] = 'Ẳ';

        $phothong[9] = 'Ẵ';
        $itdung[9] = 'Ẵ';

        $phothong[10] = 'Ặ';
        $itdung[10] = 'Ặ';

        /////////////////////////////

        // Vần Â xong

        $phothong[11] = 'Ấ';
        $itdung[12] = 'Ấ';

        $phothong[12] = 'Ầ';
        $itdung[11] = 'Ầ';

        $phothong[13] = 'Ậ';
        $itdung[13] = 'Ậ';

        $phothong[14] = 'Ẩ';
        $itdung[14] = 'Ẩ';

        $phothong[15] = 'Ẫ';
        $itdung[15] = 'Ẫ';


        /////////////////////

        // Vần E xong

        $phothong[16] = 'É';
        $itdung[16] = 'É';

        $phothong[17] = 'È';
        $itdung[17] = 'È';

        $phothong[18] = 'Ẻ';
        $itdung[18] = 'Ẻ';

        $phothong[19] = 'Ẽ';
        $itdung[19] = 'Ẽ';

        $phothong[20] = 'Ẹ';
        $itdung[20] = 'Ẹ';


        // ////////////////////////

        // Vần Ê xong

        $phothong[21] = 'Ế';
        $itdung[21] = 'Ế';

        $phothong[22] = 'Ề';
        $itdung[22] = 'Ề';

        $phothong[23] = 'Ể';
        $itdung[23] = 'Ể';

        $phothong[24] = 'Ễ';
        $itdung[24] = 'Ễ';

        $phothong[25] = 'Ệ';
        $itdung[25] = 'Ệ';


        // //////////////////////

        // Vần O xong

        $phothong[26] = 'Ó';
        $itdung[26] = 'Ó';

        $phothong[27] = 'Ò';
        $itdung[27] = 'Ò';

        $phothong[28] = 'Ỏ';
        $itdung[28] = 'Ỏ';

        $phothong[29] = 'Õ';
        $itdung[29] = 'Õ';

        $phothong[30] = 'Ọ';
        $itdung[30] = 'Ọ';

        // ////////////////

        // Vần Ô xong

        $phothong[31] = 'Ố';
        $itdung[31] = 'Ố';

        $phothong[32] = 'Ồ';
        $itdung[32] = 'Ồ';

        $phothong[33] = 'Ổ';
        $itdung[33] = 'Ổ';

        $phothong[34] = 'Ỗ';
        $itdung[34] = 'Ỗ';

        $phothong[35] = 'Ộ';
        $itdung[35] = 'Ộ';

        // //////////////////////

        // Vần Ơ xong

        $phothong[36] = 'Ớ';
        $itdung[36] = 'Ớ';

        $phothong[37] = 'Ờ';
        $itdung[37] = 'Ờ';

        $phothong[38] = 'Ở';
        $itdung[38] = 'Ở';

        $phothong[39] = 'Ỡ';
        $itdung[39] = 'Ỡ';

        $phothong[40] = 'Ợ';
        $itdung[40] = 'Ợ';

        // ////////////////////

        // Vần I xong

        $phothong[41] = 'Í';
        $itdung[41] = 'Í';

        $phothong[42] = 'Ì';
        $itdung[42] = 'Ì';

        $phothong[43] = 'Ỉ';
        $itdung[43] = 'Ỉ';

        $phothong[44] = 'Ĩ';
        $itdung[44] = 'Ĩ';

        $phothong[45] = 'Ị';
        $itdung[45] = 'Ị';

        // // //////////////////

        // Vần U xong

        $phothong[46] = 'Ú';
        $itdung[46] = 'Ú';

        $phothong[47] = 'Ù';
        $itdung[47] = 'Ù';

        $phothong[48] = 'Ủ';
        $itdung[48] = 'Ủ';

        $phothong[49] = 'Ũ';
        $itdung[49] = 'Ũ';

        $phothong[50] = 'Ụ';
        $itdung[50] = 'Ụ';

        // ///////////////

        // Vần Ư xong

        $phothong[51] = 'Ứ';
        $itdung[51] = 'Ứ';

        $phothong[52] = 'Ừ';
        $itdung[52] = 'Ừ';

        $phothong[53] = 'Ử';
        $itdung[53] = 'Ử';

        $phothong[54] = 'Ữ';
        $itdung[54] = 'Ữ';

        $phothong[55] = 'Ự';
        $itdung[55] = 'Ự';

        // ////////////////////

        // Vần Y xong

        $phothong[56] = 'Ý';
        $itdung[56] = 'Ý';

        $phothong[57] = 'Ỳ';
        $itdung[57] = 'Ỳ';

        $phothong[58] = 'Ỷ';
        $itdung[58] = 'Ỷ';

        $phothong[59] = 'Ỹ';
        $itdung[59] = 'Ỹ';

        ///////////////////////

        for ($j = 0; $j < 60; $j++) { // thực hiện chạy vòng lặp để đổi
            $pattern = '/' . $phothong[$j] . '/';
            $str = preg_replace($pattern, $itdung[$j], $str);
        }

        return $str; // trả kết quả về
    }

    function vn_rmv_wsp($str)
    { // bỏ khoảng trắng dư thừa
        $str2 = trim($str, ' '); // bỏ khoảng trắng trước và sau chuỗi
        $rs = preg_replace('/\s+/', ' ', $str2); // loại bỏ khoảng trắng thừa trong chuỗi

        return $rs;
    }



    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////



    function vn_low_rmv($str)
    { // loại bỏ khoảng trắng, chuyển sang ký tự thường 
        $str2 = $this->vn_rmv_wsp($str); // bỏ khoảng trắng trước và sau chuỗi
        $rs = mb_strtolower($str2, 'UTF-8'); // chuyển thành ký tự thường

        return $rs;
    }



    ////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////



    // áp dụng được cho một từ hoặc chuỗi nhiều từ
    function vn_num_char($str)
    { // số lượng ký tự của chuỗi, một từ hay chuỗi nhiều từ đều được
        $str2 = $this->pop_hex_convert($str); // chuyển về dạng mã hóa hex tiêu chuẩn
        $rs = mb_strlen($str2, 'UTF-8');

        return $rs;
    }



    ////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////



    // áp dụng được cho một từ hoặc chuỗi nhiều từ
    function vn_remove_accents($str)
    { // xóa dấu của một ký tự hoặc một từ, hoặc chuỗi
        $timbre = $this->vna_hex_timbre(); // mã hóa hex của dấu tiếng Việt
        $hex = bin2hex($this->rarely_hex_convert($str)); // chuyển sang mã hex để tìm dấu

        foreach ($timbre as $tim) { // tách thành các dấu
            $pt = '/' . $tim . '/'; // tạo mẫu
            if (preg_match($pt, $hex)) { //so khớp
                $hex = preg_replace($pt, '', $hex); // khử dấu của $hex; nó vẫn đang ở dạng hex
            }
        }

        $rs = $this->pop_hex_convert(hex2bin($hex)); // chuyển về từ mã hóa phổ thông
        if ($rs == "") {
            $rs = $str;
        } // dự phòng

        return $rs;
    }



    ////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////



    // áp dụng được cho một từ hoặc chuỗi nhiều từ
    // được thiết kế hướng đến một từ
    function vn_num_acc_char($str)
    { // tìm số lượng ký tự có dấu trong một từ
        $rs = 0; // rs có thể lớn hơn 1, những từ đơn có hơn một dấu sẽ được xem là lỗi chính tả
        $acc = $this->vna_acc_char_array(); // lấy mảng các nguyên âm đơn có dấu
        $strx = $this->pop_hex_convert($str); // chuyển về dạng mã hóa phổ biến, và chuyển về ký tự thường
        foreach ($acc as $acc_char) {
            $pt = '/' . $acc_char . '/';
            if (preg_match_all($pt, $strx)) {
                $rs += preg_match_all($pt, $strx);
            }
        }

        return $rs;
    }



    ////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////



    // áp dụng được cho một từ hoặc chuỗi nhiều từ
    // được thiết kế hướng đến một từ
    function vn_num_none_acc_vowel($str)
    { // tìm số lượng các nguyên âm đơn không dấu trong một từ
        $rs = 0;
        $none_acc_vowel = $this->vna_vowel_lett(); // các nguyên âm đơn không dấu mã hóa phổ thông
        $strx = $this->pop_hex_convert($str); // chuyển về dạng mã hóa phổ biến, và chuyển về ký tự thường
        foreach ($none_acc_vowel as $nav) {
            $pt = '/' . $nav . '/';
            if (preg_match_all($pt, $strx)) {
                $rs += preg_match_all($pt, $strx);
            }
        }

        return $rs; // trả về số lượng các nguyên âm đơn không dấu 
    }




    /////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////



    // áp dụng được với một từ, cũng như một chuỗi nhiều từ
    function vn_foreign_check_low($str)
    { // trả về 1 nếu có ký tự nước ngoài, trả về 0 nếu không có
        $rs = 0;
        $fr = array("f", "j", "w", "z"); // tìm các ký tự nước ngoài, bình thường không có trong tiếng Việt
        $hex = $this->pop_hex_convert($str); // chuyển về dạng viết thường, mã hóa phổ biến
        foreach ($fr as $fr2) {
            $pt = '/' . $fr2 . '/';
            $r = preg_match($pt, $hex);
            if ($r > 0) {
                $rs = 1;
                break;
            } // chỉ cần có một ký tự nước ngoài là đủ để tên không phải là dạng thuần Việt    
        }

        return $rs;
    }

    function vn_vowel_next_other($str)
    { // kiểm tra các nguyên âm có đứng cạnh nhau hay không
        // kiểm tra luôn số lượng nguyên âm được phép chỉ nằm trong khoảng từ 1 - 3
        $rs = 0; // mặc định là không đứng cạnh nhau
        // nguyên âm có dấu (60) và không dấu (12) gồm 72 ký tự đơn
        // cộng mảng các nguyên âm không dấu và có dấu để ra mảng nguyên âm chung
        $vowel = array_merge($this->vna_acc_char_array(), $this->vna_vowel_lett());
        $str2 = $this->pop_hex_convert($str); // chuyển về dạng mã hóa phổ biến, ký tự thường
        $str3 = preg_split('//u', $str2, -1, PREG_SPLIT_NO_EMPTY); // tách từng ký tự
        $post_first_vowel = null;
        $j = 0;
        foreach ($str3 as $char) {
            $j++;
            if (in_array($char, $vowel)) {
                $post_first_vowel = $j;
                break;
            } // ngắt vòng lặp, tìm được vị trí nguyên âm đầu tiên
        }

        // tính tổng số nguyên âm, gồm cả có dấu lẫn không dấu        
        $k = 0;
        $total_number_vowels = $this->vn_num_none_acc_vowel($str2) + $this->vn_num_acc_char($str2);

        if ($total_number_vowels == 1 || $total_number_vowels == 0) {
            $rs = 1;
        } // trường hợp này luôn đúng với hàm kiểm tra này

        if ($total_number_vowels == 2) { // tức là có tổng hai nguyên âm tất cả
            foreach ($str3 as $char2) {
                $k++;
                if (in_array($char2, $vowel)) {
                    $post_two_vowel = $k;
                } // tìm được vị trí nguyên âm cuối cùng, tức nguyên âm thứ 2
            }

            if ($post_two_vowel == ($post_first_vowel + 1)) {
                $rs = 1;
            }
        }

        $m = 0;
        if ($total_number_vowels == 3) { // tức là có tổng ba nguyên âm tất cả
            foreach ($str3 as $char3) {
                $m++;
                if (in_array($char3, $vowel)) {
                    $post_last_vowel = $m;
                } // tìm được vị trí nguyên âm cuối cùng, tức nguyên âm thứ 3
            }

            if ($post_last_vowel == ($post_first_vowel + 2)) {
                $rs = 1;
            } // như thế này có nghĩa là 3 nguyên âm cạnh nhau
        }

        return $rs;
    }
}
