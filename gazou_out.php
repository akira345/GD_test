<?php
//画像出力用ＰＨＰ
//2006/10/19 携帯機種判別ルーチンを同居

$in_file = $_GET["in_img"];
$out_width = $_GET["out_width"];
$out_height = $_GET["out_height"];
$out_type = $_GET["out_type"];

If ($in_file != ""){

	//出力画像拡張子無指定の場合は自力判別
	If ($out_type == ""){
		$out_type = keitai_gazo_chk();
		}
	//出力サイズ指定が無ければ、そのままのサイズを表示
	If (($out_width == "" OR $out_width <= 0) OR ($out_height == "" OR $out_height <=0)){
		//入力画像のサイズと種類を取得
		List($out_width,$out_height,$type) = GetImageSize($in_file);
	}

}

create_mini_img($in_file,$out_width,$out_height,$out_type);

function create_mini_img($in_img,$out_width,$out_height,$out_type){

//画像を読み込み、指定されたサイズの指定された画像形式で出力
//引数：入力元画像、出力サイズ幅、出力サイズ横、出力画像形式(JPG,JPEG,PNG)
//制限：入力元画像は今のところGIF,JPG,PNGにしか対応していない。

//引数チェック
	$chk = "NG";

		If ($out_width > 0 and $out_height > 0 and $in_img != "" and $out_type != ""){
		//サイズ指定がゼロ以上、入力パラメタがNULL以外
			//対応フォーマット以外のタイプが指定されていないかチェック
			$out_type = strtolower($out_type);
// GIFの出力は出来なくなった
//			If ($out_type == "jpg" OR $out_type == "jpeg" OR $out_type == "gif" OR $out_type == "png"){
			If ($out_type == "jpg" OR $out_type == "jpeg" OR $out_type == "png"){
				
				$chk = "OK";
			}
		}
	If ($chk != "OK"){

		//引数がおかしかったら失敗
		Return FALSE;
	}

//一応画像元を確認
	If (@GetImageSize($in_img) == FALSE){

	//画像が不正な場合又は開けなかった
		Return FALSE;
	}

	//入力画像のサイズと種類を取得
	List($width,$height,$type) = GetImageSize($in_img);


	If ($width > 0 And $height > 0){
	//取得したサイズがゼロ以上なら処理開始
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
				//変換不可画像	
				return false;
				break;
		}
	}else{
		//サイズ取得不可
		return false;
	}

	//リサイズもとの画像を作成
	//画質を考慮し、TrueColoeで作成
	$dst = ImageCreateTrueColor($out_width,$out_height);
	
	//元画像をコピー
	ImageCopyResized($dst,$src,0,0,0,0,$out_width,$out_height,$width,$height);

	//出力
	switch ($out_type){
		case "jpg":
		case "jpeg":
			//jpg
			header("Content-type: image/jpeg");
			ImageJPEG($dst);
			break;
//GIFの出力は出来なくなった
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

	//イメージを破棄
	ImageDestroy($src);
	ImageDestroy($dst);
}
Function keitai_gazo_chk(){
// ========================================================================
// 携帯のユーザエージェントを判別し、適切な画像拡張子を返す。
//
//
// ========================================================================
	//環境変数ユーザエージェントを代入
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	
	if (ereg("Vodafone",$user_agent)) { // J-SKY判別
	//拡張子はPNG
		return("png");
	} elseif (ereg("DoCoMo",$user_agent)) { // i-mode判別
	//拡張子はjpg
		return("jpg");
	} elseif (ereg("J-PHONE",$user_agent)) { // J-SKY判別
	//拡張子はPNG
		return("png");
	} elseif (ereg("SoftBank",$user_agent)) { // SoftBank追加 2006/11/29
	//拡張子はPNG
		return("png");
	} elseif (ereg("MOT-",$user_agent)) { // J-SKYモトローラ判別
	//拡張子はPNG
		return("png");
	} elseif (ereg("UP\.Browser",$user_agent)) { // EZweb判別
		if (ereg("KDDI|OPWV|MMP",$user_agent)) { // WAP2端末判別
			//拡張子はpng
			return("png");
		} else { // HDML端末判別
			//拡張子はpng
			return("png");
		}
	} else { // その他のブラウザ処理
		//その他はJPG
		return("jpg");
	}

}
?>