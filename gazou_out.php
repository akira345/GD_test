<?php
//�摜�o�͗p�o�g�o
//2006/10/19 �g�ы@�픻�ʃ��[�`���𓯋�

$in_file = $_GET["in_img"];
$out_width = $_GET["out_width"];
$out_height = $_GET["out_height"];
$out_type = $_GET["out_type"];

If ($in_file != ""){

	//�o�͉摜�g���q���w��̏ꍇ�͎��͔���
	If ($out_type == ""){
		$out_type = keitai_gazo_chk();
		}
	//�o�̓T�C�Y�w�肪������΁A���̂܂܂̃T�C�Y��\��
	If (($out_width == "" OR $out_width <= 0) OR ($out_height == "" OR $out_height <=0)){
		//���͉摜�̃T�C�Y�Ǝ�ނ��擾
		List($out_width,$out_height,$type) = GetImageSize($in_file);
	}

}

create_mini_img($in_file,$out_width,$out_height,$out_type);

function create_mini_img($in_img,$out_width,$out_height,$out_type){

//�摜��ǂݍ��݁A�w�肳�ꂽ�T�C�Y�̎w�肳�ꂽ�摜�`���ŏo��
//�����F���͌��摜�A�o�̓T�C�Y���A�o�̓T�C�Y���A�o�͉摜�`��(JPG,JPEG,PNG)
//�����F���͌��摜�͍��̂Ƃ���GIF,JPG,PNG�ɂ����Ή����Ă��Ȃ��B

//�����`�F�b�N
	$chk = "NG";

		If ($out_width > 0 and $out_height > 0 and $in_img != "" and $out_type != ""){
		//�T�C�Y�w�肪�[���ȏ�A���̓p�����^��NULL�ȊO
			//�Ή��t�H�[�}�b�g�ȊO�̃^�C�v���w�肳��Ă��Ȃ����`�F�b�N
			$out_type = strtolower($out_type);
// GIF�̏o�͂͏o���Ȃ��Ȃ���
//			If ($out_type == "jpg" OR $out_type == "jpeg" OR $out_type == "gif" OR $out_type == "png"){
			If ($out_type == "jpg" OR $out_type == "jpeg" OR $out_type == "png"){
				
				$chk = "OK";
			}
		}
	If ($chk != "OK"){

		//�������������������玸�s
		Return FALSE;
	}

//�ꉞ�摜�����m�F
	If (@GetImageSize($in_img) == FALSE){

	//�摜���s���ȏꍇ���͊J���Ȃ�����
		Return FALSE;
	}

	//���͉摜�̃T�C�Y�Ǝ�ނ��擾
	List($width,$height,$type) = GetImageSize($in_img);


	If ($width > 0 And $height > 0){
	//�擾�����T�C�Y���[���ȏ�Ȃ珈���J�n
		switch( $type){
			case 1:
				//GIF
				$src = ImageCreateFromGIF($in_img);
				break;
			case 2:
				//jpg
				$src = ImageCreateFromJPEG($in_img);
				break;
			case 3:
				//png
				$src = ImageCreateFromPNG($in_img);
				break;
			default:
				//�ϊ��s�摜	
				return false;
				break;
		}
	}else{
		//�T�C�Y�擾�s��
		return false;
	}

	//���T�C�Y���Ƃ̉摜���쐬
	//�掿���l�����ATrueColoe�ō쐬
	$dst = ImageCreateTrueColor($out_width,$out_height);
	
	//���摜���R�s�[
	ImageCopyResized($dst,$src,0,0,0,0,$out_width,$out_height,$width,$height);

	//�o��
	switch ($out_type){
		case "jpg":
		case "jpeg":
			//jpg
			header("Content-type: image/jpeg");
			ImageJPEG($dst);
			break;
//GIF�̏o�͂͏o���Ȃ��Ȃ���
//		case "gif":
//			header("Content-type: image/gif");
//			ImageGIF($dst);
//		print ("T1");
//			break;
		case "png":
			//png
			header("Content-type: image/png");
			ImagePNG($dst);
			break;
	}

	//�C���[�W��j��
	ImageDestroy($src);
	ImageDestroy($dst);
}
Function keitai_gazo_chk(){
// ========================================================================
// �g�т̃��[�U�G�[�W�F���g�𔻕ʂ��A�K�؂ȉ摜�g���q��Ԃ��B
//
//
// ========================================================================
	//���ϐ����[�U�G�[�W�F���g����
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	
	if (ereg("Vodafone",$user_agent)) { // J-SKY����
	//�g���q��PNG
		return("png");
	} elseif (ereg("DoCoMo",$user_agent)) { // i-mode����
	//�g���q��jpg
		return("jpg");
	} elseif (ereg("J-PHONE",$user_agent)) { // J-SKY����
	//�g���q��PNG
		return("png");
	} elseif (ereg("SoftBank",$user_agent)) { // SoftBank�ǉ� 2006/11/29
	//�g���q��PNG
		return("png");
	} elseif (ereg("MOT-",$user_agent)) { // J-SKY���g���[������
	//�g���q��PNG
		return("png");
	} elseif (ereg("UP\.Browser",$user_agent)) { // EZweb����
		if (ereg("KDDI|OPWV|MMP",$user_agent)) { // WAP2�[������
			//�g���q��png
			return("png");
		} else { // HDML�[������
			//�g���q��png
			return("png");
		}
	} else { // ���̑��̃u���E�U����
		//���̑���JPG
		return("jpg");
	}

}
?>