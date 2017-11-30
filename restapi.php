<?php
$mres = array();


//=========================================================================
function publishRunStepperMotorBasic($prango_url,$global,$local,$client,$streamindex,$dir,$dbs,$nos,$ss)
//=========================================================================
{
  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'GET'
      )
  );

  $request_message_type = 6;
  $request_message_url = "$prango_url?request_message_type=$request_message_type";

  $context  = stream_context_create($options);
  $result = file_get_contents($request_message_url, false, $context);
  if ($result === FALSE) { echo "could not get message structure"; }

  $message_structure_json = json_decode($result);
  $array_GET = array('message_type'=>$request_message_type,
                      'global'=>$global,
                      'local'=>$local,
                      'client'=>$client,
                      'stream_index'=>$streamindex);

  $fields = $message_structure_json->fields;
  //Loop through all fields of message structure
  foreach ($fields as $field){

      if ($field->field_type == 'float')
          $array_GET[$field->field_name] = 0.0;
      elseif ($field->field_type == 'string')
          $array_GET[$field->field_name] = "";
      else
          $array_GET[$field->field_name] = 0;
  }
  // CLOCKWISE = 0;
  // COUNTER_CLOCKWISE = 1;
  // FULL_STEP = 0;
  // HALF_STEP = 1;
  // QUARTER_STEP = 2;
  $array_GET['direction'] = $dir;
  $array_GET['delay_between_steps'] = $dbs;
  $array_GET['number_of_steps'] = $nos;
  $array_GET['stepSize'] = $ss;
  $data = http_build_query($array_GET);

  $context  = stream_context_create($options);
  $result = file_get_contents("$prango_url?$data", false, $context);
  if ($result === FALSE) { echo "could not publish "; }
  // status code 201 indicates that everything went fine
  //var_dump($http_response_header);
}

//=========================================================================
function publishTrigger($prango_url,$global,$local,$client,$streamindex)
//=========================================================================
{
  $options = array(
      'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'GET'
      )
  );

  $request_message_type = 11; // trigger
  $request_message_url = "$prango_url?request_message_type=$request_message_type";

  $context  = stream_context_create($options);
  $result = file_get_contents($request_message_url, false, $context);
  if ($result === FALSE) { echo "could not get message structure"; }

  $message_structure_json = json_decode($result);
  $array_GET = array('message_type'=>$request_message_type,
                      'global'=>$global,
                      'local'=>$local,
                      'client'=>$client,
                      'stream_index'=>$streamindex);

  $fields = $message_structure_json->fields;
  //Loop through all fields of message structure
  foreach ($fields as $field){

      if ($field->field_type == 'float')
          $array_GET[$field->field_name] = 0.0;
      elseif ($field->field_type == 'string')
          $array_GET[$field->field_name] = "";
      else
          $array_GET[$field->field_name] = 0;
  }

  $array_GET['timestamp'] = 14;
  $data = http_build_query($array_GET);

  $context  = stream_context_create($options);
  $result = file_get_contents("$prango_url?$data", false, $context);
  if ($result === FALSE) { echo "could not publish "; }
  // status code 201 indicates that everything went fine
  //var_dump($http_response_header);
}

//=========================================================================
function getLatestValue($prango_url,$rest_url,$global,$local,$client,$messagetype,$streamindex)
//=========================================================================
{
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
    )
  );
  $rest_url = $rest_url.'/liststreams';
  $request_message_url = "$prango_url?request_message_type=$messagetype";
  $context  = stream_context_create($options);
  $result = file_get_contents($rest_url, false, $context);
  $streams = json_decode($result);

  $res = 999;
  $n = 0;
  foreach ($streams as $stream)
  {
    if(($global == $stream->nb_global) && ($local == $stream->nb_local) && ($client == $stream->nb_client_id) && ($messagetype == $stream->nb_message_type) && ($streamindex == $stream->nb_stream_index))
    {
      $res = $stream->latestvalue;
      $n++;
    }
  }
  if($n == 1)
    {
      if($messagetype != 11)$res = number_format($res, 2,'.','');
      return $res;
    }
  else
    return 998;
}

//=========================================================================
function getLatestValueTs($prango_url,$rest_url,$global,$local,$client,$messagetype,$streamindex)
//=========================================================================
{
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
    )
  );
  $rest_url = $rest_url.'/liststreams';
  $request_message_url = "$prango_url?request_message_type=$messagetype";
  $context  = stream_context_create($options);
  $result = file_get_contents($rest_url, false, $context);
  $streams = json_decode($result);

  $res = 999;
  $n = 0;
  foreach ($streams as $stream)
  {
    if(($global == $stream->nb_global) && ($local == $stream->nb_local) && ($client == $stream->nb_client_id) && ($messagetype == $stream->nb_message_type) && ($streamindex == $stream->nb_stream_index))
    {
      $res = $stream->update_ts;
      $n++;
    }
  }
  if($n == 1)
    {
      $d = new DateTime($res);
      $res = $d->format('Y-m-d H:i:s');
      return $res;
    }
  else
    return 997;
}
//=========================================================================
function getAllStreams($prango_url,$rest_url)
//=========================================================================
{
  $ix_local   = 1;
  $ix_value   = 2;
  $ix_msgtype = 3;
  $ix_global  = 4;
  $ix_client  = 5;

  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
    )
  );
  $rest_url = $rest_url.'/liststreams';
  $request_message_url = "$prango_url?request_message_type=$messagetype";
  $context  = stream_context_create($options);
  $result = file_get_contents($rest_url, false, $context);
  $streams = json_decode($result);


  $n = 0;
  foreach ($streams as $stream)
  {
    if($stream->nb_message_type < 5 )
    {
      $n++;
      $mres[$n][$ix_local] = $stream->nb_local;
      $mres[$n][$ix_global] = $stream->nb_global;
      $mres[$n][$ix_msgtype] = $stream->nb_message_type;
      $mres[$n][$ix_client] = $stream->nb_client_id;
      $mres[$n][$ix_value] = number_format($stream->latestvalue, 2,'.','');
    }
  }
  $mres[0][1] = $n;
  $mres[0][2] = 2;
  return $mres;
}
//=========================================================================
function getLabel($client,$streamindex)
//=========================================================================
{
  // Nytomta
  if ($client == "nixie2" && $streamindex == 0) $label = "el";
  if ($client == "D2" && $streamindex == 0) $label = "panna ut";
  if ($client == "D2" && $streamindex == 1) $label = "element ut";
  if ($client == "D2" && $streamindex == 2) $label = "panna in";
  if ($client == "D2" && $streamindex == 3) $label = "pannrum";
  if ($client == "D2" && $streamindex == 4) $label = "element in";
  if ($client == "D8" && $streamindex == 0) $label = "lab";
  if ($client == "D8" && $streamindex == 1) $label = "ute";
  if ($client == "D10" && $streamindex == 0) $label = "hus vardagsrum";
  if ($client == "esp3" && $streamindex == 0) $label = "hus sovrum";

  // Kil
  if ($client == "esp2" && $streamindex == 0) $label = "el";
  if ($client == "esp4" && $streamindex == 0) $label = "panna ut";
  if ($client == "esp4" && $streamindex == 1) $label = "sovrum";
  if ($client == "esp4" && $streamindex == 2) $label = "skorsten";
  if ($client == "esp4" && $streamindex == 3) $label = "panna in";
  return($label);
}
//=========================================================================
function getPlaceAllStreams($prango_url,$rest_url,$place,$mode)
//=========================================================================
{
  $ix_local   = 1;
  $ix_value   = 2;
  $ix_msgtype = 3;
  $ix_global  = 4;
  $ix_client  = 5;
  $ix_stream_index = 6;
  $ix_label = 7;
  $ix_total = 8;

  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'GET'
    )
  );
  $rest_url = $rest_url.'streams';
  //$request_message_url = "$prango_url?streams=$messagetype";
  $context  = stream_context_create($options);
  $result = file_get_contents($rest_url, false, $context);
  $streams = json_decode($result);


  $n = 0;
  foreach ($streams as $stream)
  {
    if($stream->message_type > 3  && $stream->message_type < 9  && $stream->global == $place && $stream->update_ts > "2017-11-01")
    {
      $n++;
      $mres[$n][$ix_local] = $stream->local;
      $mres[$n][$ix_global] = $stream->global;
      $mres[$n][$ix_msgtype] = $stream->message_type;
      $mres[$n][$ix_client] = $stream->client_id;
      $mres[$n][$ix_stream_index] = $stream->stream_index;
      $mres[$n][$ix_value] = number_format($stream->latest_value, 2,'.','');
      $mres[$n][$ix_label] = getLabel($stream->client_id,$stream->stream_index);

      if ($mode == 1) {
        $mres[$n][$ix_total] =  $mres[$n][$ix_label];
      }
      else {
        $mres[$n][$ix_total] =  $mres[$n][$ix_client]." ".$mres[$n][$ix_stream_index];
      }
    }
  }
  $mres[0][1] = $n;
  //$mres[0][2] = 5;
  return $mres;
}
?>
