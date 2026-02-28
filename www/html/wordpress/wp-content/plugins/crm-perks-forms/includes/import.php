<?php
if( !defined( 'ABSPATH' ) ) exit;
class cfx_form_import{
    
public function gf_import(){
   $fixed=cfx_form::post('fixed');  
   $form_id=cfx_form::post('form_id');  
   $form=cfx_form::post('form');  
   $page=(int)cfx_form::post('page');  
   $entry_type=cfx_form::post('entry_type');  
     global $wpdb; 
     $tablen=$wpdb->prefix.'rg_lead_notes';
     $tablel=$wpdb->prefix.'rg_lead';
     $tablef=$wpdb->prefix.'rg_form_meta';
     $tabled=$wpdb->prefix.'rg_lead_detail';
     

     $tableff=cfx_form::table_name('forms');
     
     $time=current_time('mysql');
 
 include_once(cfx_form_plugin_dir.'includes/form-templates.php');
 
   if(empty($form_id)){ $form_id=reset($fixed); }
   $per_page=300;
   $form_ids_arr=array(); $ret=array('status'=>'ok');
     $form_ids='';
   if(!empty($fixed)){ 
       foreach($fixed as $v){
        $form_ids_arr[]=(int)$v;   
       }
    $form_ids=implode(', ',$form_ids_arr);   
     
if($form == 'gf_forms'){
$this->import_forms($form_ids,$tablef,$tableff,$config,$settings,$notify); 
 }
else if($form == 'cf7'){
foreach($fixed as $id){
$form_text=get_post_meta($id,'_form',true); 
if(method_exists('WPCF7_FormTagsManager','get_instance')){
    $manager=WPCF7_FormTagsManager::get_instance(); 
$contents=$manager->scan($form_text); 
$tags=$manager->get_scanned_tags();   

}else if(method_exists('WPCF7_ShortcodeManager','get_instance')){ //
 $manager = WPCF7_ShortcodeManager::get_instance();
$contents=$manager->do_shortcode($form_text);
$tags=$manager->get_scanned_tags();    
}

if(is_array($tags)){
    $cf_fields=array(); $no=1;
  foreach($tags as $tag){
     if(is_object($tag)){ $tag=(array)$tag; }
   if(!empty($tag['name'])){
       $field=array();
       $field['label']=ucwords(str_replace(array('-','_')," ",$tag['name']));
       $field['type']=$tag['basetype'];
       $field['required']=strpos($tag['type'],'*') !==false ? 'yes' : '';
         $str=''; 
     if(!empty($tag['labels']) &&in_array($field['type'],array('radio','checkbox','select'))){
       foreach($tag['labels'] as $op){
        $str.=$op."\r\n";   
       }  
     }
  $field['field_val']=$str;
   $cf_fields[$no]=$field; 
   $no++;   
   }   
} 
//var_dump($cf_fields); die();
$form_arr=array('name'=>get_the_title( $id ),'fields'=>json_encode($cf_fields),'config'=>$config[0],'settings'=>$settings[0],'notify'=>$notify[0],'time'=>$time,'status'=>'1');
$wpdb->insert($tableff,$form_arr);
}

}
    
}
else if($form == 'gf_entries' && class_exists('vxcf_form')){
$tablenn=$wpdb->prefix.vxcf_form::$id."_notes";
$tabledd=$wpdb->prefix.vxcf_form::$id."_detail";
$tablell=$wpdb->prefix.vxcf_form::$id;
$op=$this->import_forms($form_ids,$tablef,$tableff,$config,$settings,$notify); 
 // $op=get_option(cfx_form::$id.'_gf'); //import entries and id=353
 $sql='select * from '.$tablel.' where form_id in('.$form_ids.')  order by id asc limit '.$page.', '.$per_page;
$res=$wpdb->get_results($sql,ARRAY_A);
//var_dump($op); die();
if(count($res) == $per_page){
 $ret['status']='working';   
 $ret['rows']=count($res);   
} 
 $leads=array(); $ids=array();
 if(!empty($res) && !empty($op['forms'])){
  foreach($res as $v){
       if(!empty($op['forms'][$v['form_id']] ) && !empty($op[$v['form_id']]['fields']) ){
      $lead=array('form_id'=>$op['forms'][$v['form_id']],'is_star'=>$v['is_starred'],'is_read'=>$v['is_read'],'created'=>$v['date_created'],'url'=>$v['source_url'],'ip'=>$v['ip'],'vis_id'=>$v['id']);
      $ua=cfx_form_front::browser_info($v['user_agent']);
     $lead['browser']=$ua['name']; 
     $lead['os']=$ua['platform']; 
     $lead['status']=$v['status'] == 'active' ? '0' : '1';
$leads[$v['id']]=$lead;  
$ids[]=$v['id'];    
       }
  }    

$detail=array(); 
if(!empty($ids)){
$sql='select * from '.$tabled.' where lead_id in('.implode(',',$ids).')';
$res=$wpdb->get_results($sql,ARRAY_A);
  if(!empty($res)){
  foreach($res as $v){
$detail[$v['lead_id']][$v['field_number']]=$v['value'];      
  }    }
} 
//$leads=array_reverse($leads);
//radio,name= combine arary
//address , product=  create multiple fields
//shipping  = Second Choice|0
//fileupload= file
$sqls=array(); $notes=array();
$n=0; 
foreach($leads as $k=>$lead){
    
    $gf_id=array_search($lead['form_id'],$op['forms']);
    $lead['form_id']='vf_'.$lead['form_id'];
    
    $wpdb->insert($tablell,$lead);
    $id=$wpdb->insert_id; 
    if(!empty($id) && !empty($detail[$k]) && !empty($op[$gf_id]['fields']) ){
     $gf_fields=$op[$gf_id]['fields'];    
     //$trim=$op[$gf_id]['trim'];    
     //$note=$op[$gf_id]['note'];    
  $vals=array();
  foreach($gf_fields as $cf=>$fff){
      $gf=(string)$fff['id']; 

   if($fff['type'] == 'name'){ 
       $name=array();
        foreach($detail[$k] as $field_number=>$val){
      if($field_number>=$gf && $field_number < ($gf+1)){
   $name[]=$val;       
      }
  }if(!empty($name)){
  $vals[$cf]=implode(' ',$name); }  
   }
   if(isset($detail[$k][$gf])){
       $f_val=$detail[$k][$gf];
    if( in_array($fff['type'],array('shipping','option')) && is_string($f_val) ){
  $f_val=substr($f_val,0,strpos($f_val,'|'));       
   }else if($fff['type'] == 'fileupload'){
     $f_val=json_decode($f_val,true);
      if(!empty($f_val)){ if(count($f_val)>1){ 
          $files=array();
          foreach($f_val as $file){ $files[]='<a href="'.$file.'" target="_blank">'.$file.'</a>'; }
          $notes[$k]='Multiple Files '.implode("\r\n",$files); 
      } 
      $f_val=$f_val[0];
      }  
    } 
  $vals[$cf]=$f_val;    
   }  
  }
//var_dump($detail,$vals,$gf_fields,$notes);  die();   
  $m=0; 
foreach($vals as $f_key=>$f_val){
 if(is_array($f_val)){
     if(count($f_val) == 1){
 $f_val=$f_val[0];        
     }else{
 $f_val=serialize($f_val);        
     }
 }
$sqls[$m][]='("'.$id.'","'.esc_sql($f_key).'","'.esc_sql($f_val).'")'; 
if($n == 99){
$m++;  $n=0;   
} 
$n++;    
}        
    }
}

$wpdb->show_errors(); 
 $sql='insert into `'.$tabledd.'` (`lead_id`,`name`,`value`) values ';

foreach($sqls as $v){
$wpdb->query($sql.implode(', ',$v));
}

$user_id=get_current_user_id();
//import notes
if(!empty($notes)){
    foreach($notes as $k=>$v){
    $wpdb->insert($tablenn,array('user_id'=>$user_id,'lead_id'=>$id,'note'=>$v,'created'=>$time ) );
    }
}
$sql_n='select * from '.$tablen.' n where lead_id='.$id;
$res=$wpdb->get_results($sql_n,ARRAY_A);
$notes_sql=array(); $n=$m=0; 
if(!empty($res)){
    foreach($res as $v){
   $notes_sql[$m][]="values('".$v['user_id']."','".$id."','".$v['value']."''".$time."')";
   if($n == 99){
       $m++; $n=0;
   }
   $n++;     
    }
if(!empty($notes_sql)){
 $sql='insert into `'.$tablenn.'` (`user_id`,`entry_id`,`note`,`time`) ';  
    foreach($notes_sql as $v){
$wpdb->query($sql.implode(', ',$v));        
    }
}    
}

  }
  
      
 }     
   }
return $ret;   
} 
public function import_forms($form_ids,$tablef,$tableff,$config,$settings,$notify){
    $time=current_time('mysql');
      global $wpdb; 
    $op_gf=array();
    $sql='select * from '.$tablef.' where form_id in('.$form_ids.')';
$res=$wpdb->get_results($sql,ARRAY_A);
$field_types=array('name'=>'text','website'=>'url','multiselect'=>'checkbox','phone'=>'tel','list'=>'textarea','time'=>'text','fileupload'=>'file','shipping'=>'select','section'=>'hr','option'=>'select','total'=>'text','text'=>'text','textarea'=>'textarea','radio'=>'radio','checkbox'=>'checkbox','select'=>'select','html'=>'html');
if(!empty($res)){
//$op_gf=get_option(cfx_form::$id.'_gf'); 
//if(empty($op_gf)){ $op_gf=array(); } 
$gf_forms=!empty($op_gf['forms']) ? $op_gf['forms'] : array();

foreach($res as $row){
 $meta=json_decode($row['display_meta'],true);

 if(!empty($meta['fields'])){   
 $gf_fields=array(); $cf_fields=array();
 $no=1; $trim=array(); $note=array(); 
 foreach($meta['fields'] as $k=>$v){ 
 $field=array('label'=>$v['label']);
 $type=$v['type'];

 if(in_array($v['type'],array('multiselect','radio','checkbox','select','shipping','option'))){
   $str='';
     if(!empty($v['choices'])){
       foreach($v['choices'] as $op){
        $str.=$op['value'].'='.$op['text']."\r\n";   
       }  
     }
  $field['field_val']=$str;     
 }else if( in_array($type ,array('address','product') ) ){
   foreach($v['inputs'] as $in){ 
       $ty='text';
       if(strpos($in['label'],'Country') !== false){
      $ty='country';     
       }
       if(strpos($in['label'],'State') !== false){
      $ty='state';     
       }
      $ff=array('label'=>$in['label'],'type'=>$ty);
      $gf_fields[$no]=array('id'=>$in['id'],'type'=>$ff['type']);;
   $cf_fields[$no]=$this->complete_field($ff,$v);
    $no++;    
   }
   continue; 
 }
  
if($type == 'fileupload'){
    $note[$no]=$v['id'];
}else if($type == 'shipping'){
  $trim[$no]=$v['id'];   
}
 $field['type']=isset($field_types[$type]) ? $field_types[$type] : 'text';

 $gf_fields[$no]=array('id'=>$v['id'],'type'=>$v['type']);
 $cf_fields[$no]=$this->complete_field($field,$v);  
 $no++;      
 }

$form_arr=array('name'=>$meta['title'],'fields'=>json_encode($cf_fields),'config'=>$config[0],'settings'=>$settings[0],'notify'=>$notify[0],'time'=>$time,'status'=>'1');
$wpdb->insert($tableff,$form_arr);
$new_id=$wpdb->insert_id;
//$wpdb->show_errors();
if(!empty($new_id)){ 
$gf_forms[$row['form_id']]=$new_id; 
if(!isset($op_gf[$new_id])){ $op_gf[$new_id]=array(); }
$op_gf[$row['form_id']]=array('fields'=>$gf_fields); ///,'trim'=>$trim,'note'=>$note 
 } } }
$op_gf['forms']=$gf_forms; 
//update_option(cfx_form::$id.'_gf',$op_gf);   
}
return $op_gf;
}
public function complete_field($field,$v){
     if($v['isRequired'] == true){ $field['required']='yes'; }
 if(!empty($v['errorMessage'])){ $field['err_msg']=$v['errorMessage']; }
 if(!empty($v['description'])){ $field['description']=$v['description']; }
 if(!empty($v['cssClass'])){ $field['field_class']=$v['cssClass']; }
 if(!empty($v['inputName'])){ $field['par_name']=$v['inputName']; }
 if(!empty($v['placeholder'])){ $field['hint']=$v['placeholder']; }
 if(!empty($v['content'])){ $field['html']=$v['content']; }
 if(!empty($v['allowedExtensions'])){ $field['exts']=$v['allowedExtensions']; }
 if($v['noDuplicates'] == true){ $field['dup_check']='yes'; }
 $arr=array('field_val','desc','stars','required','valid_err_msg','hint','text_height','mask','custom_mask','default','par_name','err_msg','field_class','con_class');
foreach($arr as $v){
    if(!isset($field[$v])){
    $field[$v]='';    
    }
}
return $field;
}

}
