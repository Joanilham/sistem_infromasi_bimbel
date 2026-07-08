<?php

namespace App\Traits;

trait ExportsExcel
{
    protected function x(mixed $v): string
    {
        return htmlspecialchars((string) ($v ?? ''), ENT_XML1, 'UTF-8');
    }

    protected function xmlOpen(string $title, int $cols, string $dark, string $mid, string $light): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<?mso-application progid="Excel.Sheet"?>' . "\n"
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
            . ' xmlns:o="urn:schemas-microsoft-com:office:office">' . "\n"
            . '<Styles>
  <Style ss:ID="Default"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="11"/></Style>
  <Style ss:ID="s_title"><Alignment ss:Horizontal="Center" ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="13" ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="' . $dark . '" ss:Pattern="Solid"/></Style>
  <Style ss:ID="s_info"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="10" ss:Italic="1" ss:Color="#374151"/><Interior ss:Color="' . $light . '" ss:Pattern="Solid"/></Style>
  <Style ss:ID="s_head"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/><Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="' . $mid . '" ss:Pattern="Solid"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/></Borders></Style>
  <Style ss:ID="s_data"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="10"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/></Borders></Style>
  <Style ss:ID="s_data2"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="10"/><Interior ss:Color="' . $light . '" ss:Pattern="Solid"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/></Borders></Style>
  <Style ss:ID="s_text"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="10"/><NumberFormat ss:Format="@"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/></Borders></Style>
  <Style ss:ID="s_text2"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="10"/><NumberFormat ss:Format="@"/><Interior ss:Color="' . $light . '" ss:Pattern="Solid"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E7EB"/></Borders></Style>
</Styles>' . "\n";
    }

    protected function xmlTitleRow(string $title, int $cols): string
    {
        return '<Row ss:Height="28"><Cell ss:StyleID="s_title" ss:MergeAcross="' . ($cols - 1) . '"><Data ss:Type="String">' . $this->x($title) . '</Data></Cell></Row>' . "\n";
    }

    protected function xmlInfoRow(string $info, int $cols): string
    {
        return '<Row ss:Height="18"><Cell ss:StyleID="s_info" ss:MergeAcross="' . ($cols - 1) . '"><Data ss:Type="String">' . $this->x($info) . '</Data></Cell></Row>' . "\n";
    }

    protected function xmlHeaderRow(array $headers, array $spans = []): string
    {
        $xml = '<Row ss:Height="24">';
        foreach ($headers as $index => $h) {
            $spanAttr = isset($spans[$index]) && $spans[$index] > 1 ? ' ss:MergeAcross="' . ($spans[$index] - 1) . '"' : '';
            $xml .= '<Cell ss:StyleID="s_head"' . $spanAttr . '><Data ss:Type="String">' . $this->x($h) . '</Data></Cell>';
        }
        return $xml . '</Row>' . "\n";
    }

    protected function xmlStrSpanned(mixed $v, string $style, int $span): string
    {
        $spanAttr = $span > 1 ? ' ss:MergeAcross="' . ($span - 1) . '"' : '';
        return '<Cell ss:StyleID="' . $style . '"' . $spanAttr . '><Data ss:Type="String">' . $this->x($v) . '</Data></Cell>';
    }

    protected function xmlNumSpanned(mixed $v, string $style, int $span): string
    {
        $spanAttr = $span > 1 ? ' ss:MergeAcross="' . ($span - 1) . '"' : '';
        return '<Cell ss:StyleID="' . $style . '"' . $spanAttr . '><Data ss:Type="Number">' . $this->x($v) . '</Data></Cell>';
    }

    protected function xmlStr(mixed $v, string $style): string
    {
        return '<Cell ss:StyleID="' . $style . '"><Data ss:Type="String">' . $this->x($v) . '</Data></Cell>';
    }

    protected function xmlNum(mixed $v, string $style): string
    {
        return '<Cell ss:StyleID="' . $style . '"><Data ss:Type="Number">' . $this->x($v) . '</Data></Cell>';
    }

    protected function xlsResponse(string $xml, string $filename)
    {
        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }
}
