<?php
/*
 *@author: qinxuening
 *@E-mail: 2423859713@qq.com
 *@date: 2018年11月4日
 */
namespace we;
use think\Controller;
use PHPExcel;

class Export extends Controller{
    /*
     * 导出exel
     * title 标题
     * field json格式的列名
     * list  json格式的内容
     */
    public function excel(){
        if($this->request->isPost()){
            set_time_limit ( 0 ); // 脚本执行没有时间限
            ini_set("memory_limit","-1");
            ini_set('max_execution_time', '0');
            if($_POST["title"]=="统计报表"){
                $this->excelWeeks();
                exit;
            }
//             $this->after();
            $title = $_POST["title"]?$_POST["title"]:"未命名";
            $list = $_POST["list"];
            $field = $_POST["field"];
            $search = $_POST["search"];
    
            $list = json_decode($list,true);
            $field = json_decode($field,true);
    
            //导出excel
//             import("ORG.PHPExcel.PHPExcel");
            $objPHPExcel = new PHPExcel();
            $objProps = $objPHPExcel->getProperties();
            $objProps->setCreator("crm");
            $objProps->setLastModifiedBy("crm");
            $objProps->setTitle("crm Contact");
            $objProps->setSubject($title);
            $objProps->setDescription("crm Contact Data");
            $objProps->setKeywords("crm Contact");
            $objProps->setCategory("crm");
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
    
            $objActSheet->setTitle('Sheet1');
            //获取最后一列的列名
            $end_column = \PHPExcel_Cell::stringFromColumnIndex(count($field['data']) - 1);
            //设置标题
            $objActSheet->mergeCells('A1:'.$end_column.'1');
            $objActSheet->setCellValue('A1', $title);
            //$objActSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objActSheet->getStyle('A1')->applyFromArray(
                array(
                    'font' => array (
                        'bold' => true,
                        'size' => 20
                    ),
                    'alignment' => array(
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                    )
                )
            );
    
            //设置边框
            $styleArray = array(
                'borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)),
                'alignment' => array('vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
            //设置制表信息
            $objActSheet->mergeCells('A2:'.$end_column.'2');
            $objActSheet->setCellValue('A2', "制表时间:".date("Y-m-d H:i:s"));
    
            if($search){
                $objActSheet->mergeCells('A3:'.$end_column.'3');
                $objActSheet->setCellValue('A3', $search);
            }
            $i = 3;
            $first_row = $i + 1;
            //设置列头
            //PHPExcel_Cell::stringFromColumnIndex($i);
            //$i = 2;
            foreach ($field as $k=>$v){
                $i++;
                $index = 0;
                foreach ($v as $key=>$value){
                    $column = \PHPExcel_Cell::stringFromColumnIndex($key + $index);
                    if($value["colspan"] > 0){
                        $index = $index + $value["colspan"] - 1;
                        $end_column = \PHPExcel_Cell::stringFromColumnIndex($key + $index);
                        $objActSheet->mergeCells($column.$i.':'.$end_column.$i);
                    }
                    if($value["rowspan"] > 0){
                        $objActSheet->mergeCells($column.($i-$value["rowspan"]+1).':'.$column.$i);
                        $objActSheet->setCellValue($column.($i-$value["rowspan"]+1), $value["name"]);
                        $objActSheet->getStyle($column.($i-$value["rowspan"]+1))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    }else{
                        $objActSheet->setCellValue($column.$i, $value["name"]);
                    }
                    if($value["excel_width"] > 0){
                        $objActSheet->getColumnDimension($column)->setWidth($value["excel_width"]);
                    }
                    if($value["note"]){
                        $objActSheet->getComment($column.i)->setAuthor($value["note"]["author"]);     //设置作者
                        $objCommentRichText = $objActSheet->getComment($column.i)->getText()->createTextRun($value["note"]["context"]);  //添加批注
                        // 						$objCommentRichText->getFont()->setBold($value["note"]["bold"]);  //将现有批注加粗
    
                        $objActSheet->getComment($column.i)->getFillColor()->setRGB($value["note"]["rgb"]);      //设置背景色 ，在office中有效在wps中无效
                    }
                }
            }
            //die;
            //设置列头颜色
            $objActSheet->getStyle('A'.$first_row.':'.$end_column.$i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle('A'.$first_row.':'.$end_column.$i)->getFill()->getStartColor()->setRGB('FFFFCC');
            $objActSheet->getStyle('A'.$first_row.':'.$end_column.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //内容显示
    
            foreach ($list as $k => $v) {
                $i++;
                foreach ($field['data'] as $key=>$value){
                    $column = \PHPExcel_Cell::stringFromColumnIndex($key);
                    if($value['DateType']==1&&$v[$value["field"]])
                        $objActSheet->setCellValueExplicit($column.$i, $v[$value["field"]],\PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    else
                        $objActSheet->setCellValueExplicit($column.$i, $v[$value["field"]],\PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }
    
            $objActSheet->getStyle('A4:'.$end_column.$i)->applyFromArray($styleArray);
            ob_end_clean();
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            header("Content-Type: application/vnd.ms-excel;");
            header("Content-Disposition:attachment;filename=".$title."_".date('Y-m-d',mktime()).".xls");
            header("Pragma:no-cache");
            header("Expires:0");
            $objWriter->save("php://output");
        }
    }
    
    /*
     * 导出exel
     * title 标题集合
     * field json格式的列名集合
     * field2 json格式的隐藏关联列名集合
     * list  json格式的内容集合
     */
    public function excel_more(){
        if($this->isPost()){
            set_time_limit ( 0 ); // 脚本执行没有时间限
            ini_set("memory_limit","-1");
            ini_set('max_execution_time', '0');
            $this->after();
            $title = $_POST["title"]?$_POST["title"]:"未命名";
            $titles = $_POST["titles"];
            $list = $_POST["list"];
            $field = $_POST["field"];
            $field2 = $_POST["field2"];
            $search = $_POST["search"];
    
            $list = json_decode($list,true);
            $titles = json_decode($titles,true);
            $field = json_decode($field,true);
            $field2 = json_decode($field2,true);
            $weight = 0;
            foreach ($field2 as $v){
                $weight = $weight>count($v['data'])?$weight:count($v['data']);
            }
            //导出excel
            import("ORG.PHPExcel.PHPExcel");
            $objPHPExcel = new PHPExcel();
            $objProps = $objPHPExcel->getProperties();
            $objProps->setCreator("crm");
            $objProps->setLastModifiedBy("crm");
            $objProps->setTitle("crm Contact");
            $objProps->setSubject($title);
            $objProps->setDescription("crm Contact Data");
            $objProps->setKeywords("crm Contact");
            $objProps->setCategory("crm");
            $objPHPExcel->setActiveSheetIndex(0);
            $objActSheet = $objPHPExcel->getActiveSheet();
    
            $objActSheet->setTitle('Sheet1');
            //获取最后一列的列名
            $end_column = \PHPExcel_Cell::stringFromColumnIndex($weight - 1);
            //设置标题
            $objActSheet->mergeCells('A1:'.$end_column.'1');
            $objActSheet->setCellValue('A1', $title);
            $objActSheet->getStyle('A1')->applyFromArray(
                array(
                    'font' => array ('bold' => true,'size' => 20),
                    'alignment' => array('vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                )
            );
            //设置边框
            $styleArray = array(
                'borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)),
                'alignment' => array('vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            );
    
    
            //设置制表信息
            $objActSheet->mergeCells('A2:'.$end_column.'2');
            $objActSheet->setCellValue('A2', "制表时间:".date("Y-m-d H:i:s"));
    
            if($search){
                $objActSheet->mergeCells('A3:'.$end_column.'3');
                $objActSheet->setCellValue('A3', $search);
            }
    
            $i = 3;
            foreach ($titles as $kt=>$vt){
                $i+=2;
                $first_row = $i + 2;
                $end_column = \PHPExcel_Cell::stringFromColumnIndex(count($field2[$kt]['data']) - 1);
                //------表名------
                $objActSheet->mergeCells('A'.$i.':'.$end_column.''.$i);
                $objActSheet->setCellValue('A'.$i, $vt);
                $objActSheet->getStyle('A'.$i)->applyFromArray(
                    array(
                        'font' => array ('bold' => true,'size' => 20),
                        'alignment' => array('vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
                    )
                );
                $i++;
                //------表头------
                $row = array();
                foreach ($field[$kt] as $kf=>$vf){
                    $i++;
                    $index = 0;	//colspan产生的多节
                    $rowinde = 0;//row产生的多节
                    foreach ($vf as $key=>$value){
                        //判断该单元格是否再row集合内，若是，col++
                        $rowinde += $this->f1($key+$index+$rowinde, $i, $row);
                        // 		print_r("应加row:".$rowinde."<P>");
                        $colnumber = $key+$index+$rowinde;
                        $column = \PHPExcel_Cell::stringFromColumnIndex($colnumber);
                        $end_colnumber = $colnumber;
                        if($value["colspan"] > 1){
                            $index = $index + $value["colspan"] - 1;
                            $temprow = false?1:0;
                            $end_colnumber = $colnumber + $value["colspan"] - 1;
                            $end_column = \PHPExcel_Cell::stringFromColumnIndex($end_colnumber);
                            $objActSheet->mergeCells($column.$i.':'.$end_column.$i);
                        }
                        if($value["rowspan"] > 1){
                            $cs = $end_colnumber-$colnumber+1;
                            while($cs>0){
                                $rs = $value["rowspan"];
                                while($rs>0){
                                    $row[] = array('col'=>$colnumber+$cs-1,'row'=>$i+$rs-1);
                                    $rs--;
                                }
                                $cs--;
                            }
                            $end_column = \PHPExcel_Cell::stringFromColumnIndex($end_colnumber);
                            $objActSheet->mergeCells($column.$i.':'.$end_column.($i+$value["rowspan"]-1));
                        }
                        $objActSheet->setCellValue($column.$i, $value["name"]);
    
                        if($value["excel_width"] > 0){
                            $objActSheet->getColumnDimension($column)->setWidth($value["excel_width"]);
                        }
                    }
                    // 					print_R("下一列<P>");
                }
                // 				exit;
                //------表内容------
                foreach ($list[$kt] as $kl => $vl) {
                    $i++;
                    foreach ($field2[$kt]['data'] as $key=>$value){
                        $column = \PHPExcel_Cell::stringFromColumnIndex($key);
                        // 						$objActSheet->setCellValueExplicit($column.$i, $vl[$value["field"]],PHPExcel_Cell_DataType::TYPE_STRING);
                        if($value['DateType']==1&&$v[$value["field"]])
                            $objActSheet->setCellValueExplicit($column.$i, $vl[$value["field"]],\PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        else
                            $objActSheet->setCellValueExplicit($column.$i, $vl[$value["field"]],\PHPExcel_Cell_DataType::TYPE_STRING);
    
                    }
                }
                $objActSheet->getStyle('A'.$first_row.':'.$end_column.$i)->applyFromArray($styleArray);
    
            }
            $objActSheet->getColumnDimension('A')->setWidth(13);
            while($weight>0){
                $column = \PHPExcel_Cell::stringFromColumnIndex($weight);
                $objActSheet->getColumnDimension($column)->setWidth(12);
                $weight--;
            }
    
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            header("Content-Type: application/vnd.ms-excel;");
            header("Content-Disposition:attachment;filename=".$title."_".date('Y-m-d',mktime()).".xls");
            header("Pragma:no-cache");
            header("Expires:0");
            $objWriter->save('php://output');
        }
    
    }
    
}


















































