<?php
/**
 * DHE
 *
 * @copyright Copyright (c) 2012-2014 DHE, Daniel Henninger (http://www.dhe.de)
 */

namespace Cms\Dhe;

use Zend\Debug\Debug;

class Picture
{
    public static function createThumb($pic="") {
    	//Dateiname des Ursprungsbildes
    	$bild = $pic;
    	
    	//Dateiname des Thumbnails
    	$thumb = 'bild_thumb.jpg';
    	
    	//Pfad zum Speichern
    	$savePath = "public/images/";
    	
		//Welche Breite soll das finale Thumbnail haben?
		$resWidth = 320;
		//Welche Höhe soll das finale Thumbnail haben?
		$resHeight = 215;
		
		//Aulesen der Höhe,Breite und des Dateityps des Bildes
		$size = getimagesize($bild);
		//Das erste Element des Arrays $size beinhaltet die Breite
		$width = $size['0'];
		//Das zweite Element des Arrays $size beinhaltet die Höhe
		$height = $size['1'];
		//Das zweite Element des Arrays $size beinhaltet den Typ
		$typ = $size['2'];
		
		switch($typ)
		{
			//Wenn das Bild ein GIF ist...
			case 1:
				//Erstellen des ursprünglichen Bildes
				$img = ImageCreateFromGIF($bild);
				break;
				//Wenn das Bild ein JPG ist...
			case 2:
				//Erstellen des ursprünglichen Bildes
				$img = ImageCreateFromJPEG($bild);
				break;
				//Wenn das Bild ein PNG ist...
			case 3:
				//Erstellen desursprünglichen Bildes
				$img = ImageCreateFromPNG($bild);
				break;
				//Wenn die Datei kein GIF,JPG oder PNG ist...
			default:
				//Ausgeben einer Fehlermeldung und Beenden des Scriptes
				die('Sorry, das Dateiformat wird nicht unterstützt.');
				break;
		}
		
		//Wenn die Breite größer ist als die Höhe...
		if($width > $height)
		{
			//Die Breite steht fest
			$thumbWidth = $resWidth;
			//Errechnen des Divisors
			$div = $width / $thumbWidth;
			//Errechnen der Höhe
			$thumbHeight = $height / $div;
		
			//Festlegen der X-Koordinate auf 0
			$xAnfang = 0;
			//Errechnen der Y-Koordinate an denen das temporäre Bild in das finale Thumbnail eingefügt wird
			$yAnfang = ($resHeight - $thumbHeight) / 2;
			$yAnfang = 0;
		}
		//Wenn die Höhe größer ist als die Breite...
		elseif($height > $width)
		{
			//Die Höhe steht fest
			$thumbHeight = $resHeight;
			//Errechnen des Divisors
			$div = $height / $resHeight;
			//Errechnen der Höhe
			$thumbWidth = $width / $div;
		
			//Errechnen der X-Koordinate an denen das temporäre Bild in das finale Thumbnail eingefügt wird
			$xAnfang = ($resWidth - $thumbWidth) / 2;
			//Festlegen der Y-Koordinate auf 0
			$yAnfang = 0;
		}
		//Wenn beide Seiten gleich lang sind...
		else
		{
			//Wenn die Breite größer ist als die Höhe...
			if($resWidth > $resHeight)
			{
				//Festlegen der Höhe des temporären Bildes auf die Höhe des finalen Bildes
				$thumbHeight = $resHeight;
				//Festlegen der Breite des temporären Bildes auf die Höhe des finalen Bildes
				$thumbWidth = $resHeight;
			}
			//Wenn die Höhe größer ist als die Breite...
			elseif($resHeight > $resWidth)
			{
				//Festlegen der Höhe des temporären Bildes auf die Breite des finalen Bildes
				$thumbHeight = $resWidth;
				//Festlegen der Breite des temporären Bildes auf die Breite des finalen Bildes
				$thumbWidth = $resWidth;
			}
			//Wenn beide Seite gleich sind...
			else
			{
				//Festlegen der Höhe des temporären Bildes auf die Höhe des finalen Bildes
				$thumbHeight = $resHeight;
				//Festlegen der Breite des temporären Bildes auf die Höhe des finalen Bildes
				$thumbWidth = $resHeight;
			}
		
			//Errechnen der X-Koordinate an denen das temporäre Bild in das finale Thumbnail eingefügt wird
			$xAnfang = ($resWidth - $thumbWidth) / 2;
			//$xAnfang = 0;
			//Errechnen der Y-Koordinate an denen das temporäre Bild in das finale Thumbnail eingefügt wird
			$yAnfang = ($resHeight - $thumbHeight) / 2;
			//$yAnfang = 0;
		}
		
		//Erstellen eines temporären Bildes um ein Thumbail des Bildes zu erstellen
		$tmpImg = ImageCreateTrueColor($thumbWidth,$thumbHeight);
		//Einfügen des Bildes in das temporäre Bild
		ImageCopyResampled($tmpImg,$img,0,0,0,0,$thumbWidth, $thumbHeight, $width, $height);
		
		//Das finale Thumbnail erstellen
		$resImg = ImageCreateTrueColor($resWidth, $resHeight);
		//Das neue Bild mit schwarz füllen
		ImageFill($resImg, 0, 0, ImageColorAllocate($resImg,  249, 249, 249));
		//Das temporäre Bild in das Thumbnail einfügen
		imagecopymerge($resImg,$tmpImg,$xAnfang,$yAnfang,0,0,$resWidth,$resHeight,100);
		//Das neue Bild mit schwarz füllen
		ImageFill($resImg, 0, ($yAnfang+$thumbHeight), ImageColorAllocate($resImg, 127, 140, 141));
		
		switch($typ)
		{
			//Wenn das Bild ein GIF ist...
			case 1:
				//Abspeichern des neuen Bildes
				ImageGIF($resImg,$savePath.$thumb);
				break;
				//Wenn das Bild ein JPG ist...
			case 2:
				//Abspeichern des neuen Bildes
				ImageJPEG($resImg,$savePath.$thumb);
				/*
				ob_start(); //Stdout --> buffer
				imagejpeg($resImg);
				$pic = ob_get_contents(); //store stdout in $img2
				ob_end_clean(); //clear buffer
				*/
				//$resImg = rawurlencode($resImg);
				ob_start();
				// generate the byte stream
				imagejpeg($resImg);
				// and finally retrieve the byte stream
				$pic = ob_get_clean();
				//echo "<img src='data:image/jpeg;base64," . base64_encode( $pic ) . "' />";
				imagedestroy($resImg); //destroy img

				break;
				//Wenn das Bild ein PNG ist...
			case 3:
				//Abspeichern des neuen Bildes
				ImagePNG($resImg,$savePath.$thumb);
				break;
		}
        
		//$pic = rawurlencode($pic);
		
        return $pic;
    }
    
}
