<?php

// include(dirname(__FILE__) . '/includes/ddc.php');
include(dirname(__FILE__) . '/includes/date.php');
function read($csv)
{
    $file = fopen($csv, 'r');
    while (!feof($file)) {
        $line[] = fgetcsv($file, 1024, ",");
    }
    fclose($file);
    return $line;
}
$csv = dirname(__FILE__) . '/datas_' . ddc($_POST['competition']) . '.csv';
$csv = read($csv);


/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// require_once('TCPDF/tcpdf.php');
require_once('TCPDF-master/tcpdf.php');

// create new PDF document
$pageLayout = array(101.8, 75); //  or array($height, $width) 
$pdf = new TCPDF('F', 'mm', $pageLayout, true, 'UTF-8', false);
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// // set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Nicolas Peyrebrune');
// $pdf->SetTitle('Infographie Flux carburants');
// $pdf->SetSubject('Infographie');
// $pdf->SetKeywords('Infographie, SUDOUEST, flux, carburants, match');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(0, 0, 0, 0);

// set auto page breaks
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(FALSE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/fra.php')) {
    require_once(dirname(__FILE__) . '/lang/fra.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// add a page
$pdf->AddPage();
// get esternal file content
// $utf8text = file_get_contents('TCPDF-master/examples/data/utf8test.txt', false);

// $fontname = TCPDF_FONTS::addTTFfont('/TCPDF-master/fonts/UtopiaStd-BlackHeadline.ttf', 'TrueTypeUnicode', '', 96);
// $pdf->SetFont($font_family = 'utopiastdblackheadline', '', 14, '', false);
$pdf->setFont($font_family = 'roboto', '', 7, '', false);
// $pdf->setFont($font_family='robotolight', 10, '', false);
// $pdf->setFont($font_family = 'robotomedium', 10, '', false);
// $pdf->setFont($font_family='robotoi', 10, '', false);
// $pdf->setFont($font_family='robotocondensed', '', false);
// $pdf->setFont($font_family='robotobcondensed', '', false);
// $pdf->setFont($font_family='robotoblack', '', false);
// $pdf->setFont($font_family = 'utopiastd', $font_variant = '', $font_size = 10);
// $pdf->ImageSVG('images/fond.svg', 0, 0, 101.8, 75, '', '', '', '', false);
// $pdf->SetFont('robotomedium', '', 9, '', false);
// $pdf->SetFillColor(90, 10, 65, 15);

function ImageRelace($logo)
{
    if (file_exists('images/Rugby/' . ddc($logo) . '.png')) {
        return 'images/Rugby/' . ddc($logo) . '.png';
    } else {
        return 'images/Rugby/xx.png';
    }
}

/**
 * @Documented drawMatchCells
 * 
 * @param [type] $pdf
 * @param [int] $x -> position bloc
 * @param [int] $y -> position bloc
 * @param [int] $width_team -> largeur du bloc équipe
 * @param [int] $width_score -> largeur du bloc score
 * @param [int] $height_cell -> hauteur des blocs équipe / score
 * @param [int] $space_x -> espacement entre les blocs équipe / score
 * @param [int] $space_y -> espacement entre les blocs équipe / score
 * @param [int] $match_data -> data par ligne dans le csv
 * @param [int] $border -> 0 / 1
 */

function drawMatchCells($pdf, $x, $y, $width_team, $width_score, $height_cell, $space_x, $space_y, $match_data, $border)
{

    // Définition des dimensions de l'image
    $imageWidth = 10 / 2.5; // Largeur de l'image en pouces
    $imageHeight = 15 / 2.5; // Hauteur de l'image en pouces

    // Définition des lieux et horaires
    $pdf->setCellPaddings(0, 0, 0, 0);
    $pdf->SetFont('robotoi', '', 7, '', false);
    $pdf->SetTextColor(0, 0, 0, 100);
    $pdf->SetFillColor(0, 0, 0, 0);
    $pdf->SetXY($x, $y - 3.5);
    $pdf->Cell($width_team + $width_score, $height_cell, $match_data[1], $border, 0, 'L', 1, 1, 1, false, '', 'L');

    // Définition (a. p.)
    $pdf->SetTextColor(0, 0, 0, 100);
    $pdf->SetFont('robotoi', '', 5, '', false);
    $pdf->SetXY($x + 23, $y + 9.2);
    $pdf->Cell($width_score + 1, $height_cell, $match_data[6], $border, 0, 'L', 1, 1, 1, false, '', 'L');

    // Définition de la font et couleurs pour nom des équipes et scores
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->setCellPaddings(1, 0, 1, 0);
    $pdf->SetFont('robotomedium', '', 9, '', false);
    $pdf->SetFillColor(90, 10, 65, 15);

    // #1 Equipes, scores et Ecussons 
    $pdf->SetXY($x, $y);
    $pdf->Cell($width_team, $height_cell, $match_data[2], $border, 0, 'L', 1, 1, 1, false, '', 'L');
    $pdf->SetXY($x + $width_team + $space_x, $y);
    $pdf->Cell($width_score, $height_cell, $match_data[3], $border, 0, 'L', 1, 1, 1, false, '', 'M');
    $pdf->Image(ImageRelace($match_data[2]), $x - 4.5, $y - 0.3, $imageWidth, $imageHeight, 'PNG', '', '', false, 300, '', false, false, 0, '', false, false);

    // #2 Equipes, scores et Ecussons 
    $pdf->SetXY($x, $y + $height_cell + $space_y);
    $pdf->Cell($width_team, $height_cell, $match_data[4], $border, 0, 'L', 1, 1, 1, false, '', 'L');
    $pdf->SetXY($x + $width_team + $space_x, $y + $height_cell + $space_y);
    $pdf->Cell($width_score, $height_cell, $match_data[5], $border, 0, 'L', 1, 1, 1, false, '', 'M');
    $pdf->Image(ImageRelace($match_data[4]), $x - 4.5, $y + $height_cell + $space_y, $imageWidth, $imageHeight, 'PNG', '', '', false, 300, 'M', false, false, 0, 'B', false, false);
};

/**
 * @Documented titreTour
 *
 * @param [type] $pdf
 * @param [int] $posx -> position bloc
 * @param [int] $posy -> position bloc
 * @param [int] $title -> texte du titre
 * @param [int] $justif -> 'C', 'L' ou 'R'
 * @param [int] $width -> larageur totale $width_team + $width_score
 * @param [int] $border -> 0 / 1
 */

function titreTour($pdf, $posx, $posy, $title, $justif, $width, $border)
{
    $pdf->SetFont('robotoi', '', 10, '', false);
    $pdf->SetTextColor(0, 0, 0, 100);
    $pdf->SetXY($posx, $posy);
    $pdf->Cell($width, 0, $title, $border, 0, $justif);
};

$border = 0;

$width_team = 23;
$width_score = 5;
$height_cell = 3.5;

$space_x = 0.5;
$space_y = 2;

$x_qf = 4.5;
$y_qf4 = 18;
$y_qf1 = 33;
$y_qf2 = 48;
$y_qf3 = 63;

$x_sf = 40;
$y_sf1 = 25;
$y_sf2 = 55;

$x_f = 71;
$y_f = 39.5;

$spaceHtitle = 7.5; // Espace vertical entre le titre du tour et le premier bloc rencontre


//@ Titre
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->SetFont('robotomedium', '', 18, '', false);
$pdf->SetTextColor(0, 0, 0, 0);
$pdf->SetFillColor(90, 10, 65, 15);
$pdf->SetXY(0, 0);
$pdf->Cell(101.8, 7.8, $_POST['competition'], $border, 0, 'C', 1);

//@ Epreuves 1/4
titreTour($pdf, $x_qf, $y_qf4 - $spaceHtitle, 'Quarts de finale', 'C', $width_team + $width_score, $border);
// Utilisation de la fonction pour dessiner Quart de finale 4
drawMatchCells($pdf, $x_qf, $y_qf4, $width_team, $width_score, $height_cell, $space_x, $space_y, $csv[3], $border);
// Utilisation de la fonction pour dessiner Quart de finale 1
drawMatchCells($pdf, $x_qf, $y_qf1, $width_team, $width_score, $height_cell, $space_x, $space_y, $csv[0], $border);
// Utilisation de la fonction pour dessiner Quart de finale 2
drawMatchCells($pdf, $x_qf, $y_qf2, $width_team, $width_score, $height_cell, $space_x, $space_y, $csv[1], $border);
// Utilisation de la fonction pour dessiner Quart de finale 3
drawMatchCells($pdf, $x_qf, $y_qf3, $width_team, $width_score, $height_cell, $space_x, $space_y, $csv[2], $border);


//@ Epreuves Demi-finales
titreTour($pdf, $x_sf, $y_sf1 - $spaceHtitle, 'Demi-finales', 'C', $width_team + $width_score, $border);
// Utilisation de la fonction pour dessiner Demie finale 1
drawMatchCells($pdf, $x_sf, $y_sf1, $width_team, $width_score, $height_cell, $space_x, $space_y, $csv[4], $border);
// Utilisation de la fonction pour dessiner Demie finale 2
drawMatchCells($pdf, $x_sf, $y_sf2, $width_team, $width_score, $height_cell, $space_x, $space_y, $csv[5], $border);


//@ Epreuves Finale
titreTour($pdf, $x_f, $y_f - $spaceHtitle, 'Finale', 'C', $width_team + $width_score, $border);
drawMatchCells($pdf, $x_f, $y_f, $width_team, $width_score, $height_cell, $space_x, $space_y, $csv[6], $border);

$pdf->ImageSVG('images/' . ddc($_POST['competition']) . '.svg', 76, 13, 18.5, 16.6, '', '', '', $border, false);
$pdf->ImageSVG('images/signature.svg', 86, 71, 15, 3, '', '', '', $border, false);
ob_end_clean();
// close and output PDF document

$pdf->Output('ProductionPdf/TableauFinal' . ddc($_POST['competition']) . '_' . $date . '.pdf', 'F');
// $pdf->Output('TableauFinalChampionsCup_' . $date . '.pdf', 'D');
echo '<a id="download" href="ProductionPdf/TableauFinal' . ddc($_POST['competition']) . '_' . $date . '.pdf" download="TableauFinal' . ddc($_POST['competition']) . '_' . $date . '.pdf">
<button type="button" id="submitBtn_">Télécharger</button>
</a>';
//============================================================+
// END OF FILE
//============================================================+
