<?php //pclzip_version = "1.3";

  define( 'PCLZIP_READ_BLOCK_SIZE', 2048 );
  define( 'PCLZIP_ERROR_EXTERNAL', 0 );
  define( 'PCLZIP_TEMPORARY_DIR', '' );
  define( 'PCLZIP_ERR_WRITE_OPEN_FAIL', -1 );
  define( 'PCLZIP_ERR_READ_OPEN_FAIL', -2 );
  define( 'PCLZIP_ERR_INVALID_PARAMETER', -3 );
  define( 'PCLZIP_ERR_MISSING_FILE', -4 );
  define( 'PCLZIP_ERR_FILENAME_TOO_LONG', -5 );
  define( 'PCLZIP_ERR_INVALID_ZIP', -6 );
  define( 'PCLZIP_ERR_BAD_EXTRACTED_FILE', -7 );
  define( 'PCLZIP_ERR_DIR_CREATE_FAIL', -8 );
  define( 'PCLZIP_ERR_BAD_EXTENSION', -9 );
  define( 'PCLZIP_ERR_BAD_FORMAT', -10 );
  define( 'PCLZIP_ERR_DELETE_FILE_FAIL', -11 );
  define( 'PCLZIP_ERR_RENAME_FILE_FAIL', -12 );
  define( 'PCLZIP_ERR_BAD_CHECKSUM', -13 );
  define( 'PCLZIP_ERR_INVALID_ARCHIVE_ZIP', -14 );
  define( 'PCLZIP_ERR_MISSING_OPTION_VALUE', -15 );
  define( 'PCLZIP_ERR_INVALID_OPTION_VALUE', -16 );
  define( 'PCLZIP_OPT_PATH', 77001 );
  define( 'PCLZIP_OPT_ADD_PATH', 77002 );
  define( 'PCLZIP_OPT_REMOVE_PATH', 77003 );
  define( 'PCLZIP_OPT_REMOVE_ALL_PATH', 77004 );
  define( 'PCLZIP_OPT_SET_CHMOD', 77005 );
  define( 'PCLZIP_CB_PRE_EXTRACT', 78001 );
  define( 'PCLZIP_CB_POST_EXTRACT', 78002 );
  define( 'PCLZIP_CB_PRE_ADD', 78003 );
  define( 'PCLZIP_CB_POST_ADD', 78004 );

  // --------------------------------------------------------------------------------
  class PclZip
  { var $zipname = '';
    var $zip_fd = 0;
    var $error_code = 1;
    var $error_string = '';
   function PclZip($p_zipname)
  {  if (!function_exists('gzopen'))
    { die('Abort '.basename(__FILE__).' : Missing zlib extensions');
    }
    $this->zipname = $p_zipname;
    $this->zip_fd = 0;
    return;
  }

   function extract(/* options */)
  { $v_result=1;
    $this->privErrorReset();
    if (!$this->privCheckFormat()) {
    return(0);
    }
    $v_options = array();
    $v_path = "./";
    $v_remove_path = "";
    $v_remove_all_path = false;
    $v_size = func_num_args();
    if ($v_size > 0) {
      $v_arg_list = &func_get_args();
      if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
        $v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
                                            array (PCLZIP_OPT_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_PATH => 'optional',
                                                   PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
                                                   PCLZIP_OPT_ADD_PATH => 'optional',
                                                   PCLZIP_CB_PRE_EXTRACT => 'optional',
                                                   PCLZIP_CB_POST_EXTRACT => 'optional',
                                                   PCLZIP_OPT_SET_CHMOD => 'optional' ));
        if ($v_result != 1) {
          return 0;
        }
        if (isset($v_options[PCLZIP_OPT_PATH])) {
          $v_path = $v_options[PCLZIP_OPT_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
          $v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
          $v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
        }
        if (isset($v_options[PCLZIP_OPT_ADD_PATH])) {
          if ((strlen($v_path) > 0) && (substr($v_path, -1) != '/')) {
            $v_path .= '/';
          }
          $v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
        }
      }
      else {
        $v_path = $v_arg_list[0];
        if ($v_size == 2) {
          $v_remove_path = $v_arg_list[1];
        }
        else if ($v_size > 2) {
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");
          return 0;
        }
      }
    }
    $p_list = array();
    if (($v_result = $this->privExtract($p_list, $v_path, $v_remove_path, $v_remove_all_path, $v_options)) != 1)
    {  unset($p_list);
      return(0);
    }
    return $p_list;
  }

  function errorCode()
  { if (PCLZIP_ERROR_EXTERNAL == 1) {
      return(PclErrorCode());
    }
    else {
      return($this->error_code);
    }
  }

   function errorName($p_with_code=false)
  {  $v_name = array ( PCLZIP_ERR_NO_ERROR => 'PCLZIP_ERR_NO_ERROR',
                      PCLZIP_ERR_WRITE_OPEN_FAIL => 'PCLZIP_ERR_WRITE_OPEN_FAIL',
                      PCLZIP_ERR_READ_OPEN_FAIL => 'PCLZIP_ERR_READ_OPEN_FAIL',
                      PCLZIP_ERR_INVALID_PARAMETER => 'PCLZIP_ERR_INVALID_PARAMETER',
                      PCLZIP_ERR_MISSING_FILE => 'PCLZIP_ERR_MISSING_FILE',
                      PCLZIP_ERR_FILENAME_TOO_LONG => 'PCLZIP_ERR_FILENAME_TOO_LONG',
                      PCLZIP_ERR_INVALID_ZIP => 'PCLZIP_ERR_INVALID_ZIP',
                      PCLZIP_ERR_BAD_EXTRACTED_FILE => 'PCLZIP_ERR_BAD_EXTRACTED_FILE',
                      PCLZIP_ERR_DIR_CREATE_FAIL => 'PCLZIP_ERR_DIR_CREATE_FAIL',
                      PCLZIP_ERR_BAD_EXTENSION => 'PCLZIP_ERR_BAD_EXTENSION',
                      PCLZIP_ERR_BAD_FORMAT => 'PCLZIP_ERR_BAD_FORMAT',
                      PCLZIP_ERR_DELETE_FILE_FAIL => 'PCLZIP_ERR_DELETE_FILE_FAIL',
                      PCLZIP_ERR_RENAME_FILE_FAIL => 'PCLZIP_ERR_RENAME_FILE_FAIL',
                      PCLZIP_ERR_BAD_CHECKSUM => 'PCLZIP_ERR_BAD_CHECKSUM',
                      PCLZIP_ERR_INVALID_ARCHIVE_ZIP => 'PCLZIP_ERR_INVALID_ARCHIVE_ZIP',
                      PCLZIP_ERR_MISSING_OPTION_VALUE => 'PCLZIP_ERR_MISSING_OPTION_VALUE',
                      PCLZIP_ERR_INVALID_OPTION_VALUE => 'PCLZIP_ERR_INVALID_OPTION_VALUE' );

    if (isset($v_name[$this->error_code])) {
      $v_value = $v_name[$this->error_code];
    }
    else {
      $v_value = 'NoName';
    }
     if ($p_with_code) {
      return($v_value.' ('.$this->error_code.')');
    }
    else {
      return($v_value);
    }
  }

  function errorInfo($p_full=false)
  { if (PCLZIP_ERROR_EXTERNAL == 1) {
     return(PclErrorString());
    }
    else {
      if ($p_full) {
        return($this->errorName(true)." : ".$this->error_string);
      }
      else {
        return($this->error_string." [code ".$this->error_code."]");
      }
    }
  }

// --------------------------------------------------------------------------------
  function privCheckFormat($p_level=0)
  {  $v_result = true;
     $this->privErrorReset();
    if (!is_file($this->zipname)) {
       PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "Missing archive file '".$this->zipname."'");
      return(false);
    }
    if (!is_readable($this->zipname)) {
      PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, "Unable to read archive '".$this->zipname."'");
      return(false);
    }
     return $v_result;
  }
 function privParseOptions(&$p_options_list, $p_size, &$v_result_list, $v_requested_options)
  { $v_result=1;
     $i=0;
    while ($i<$p_size) {
     if (!isset($v_requested_options[$p_options_list[$i]])) {
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid optional parameter '".$p_options_list[$i]."' for this method");
       return PclZip::errorCode();
      }
       switch ($p_options_list[$i]) {
        case PCLZIP_OPT_PATH :
        case PCLZIP_OPT_REMOVE_PATH :
        case PCLZIP_OPT_ADD_PATH :
           if (($i+1) >= $p_size) {
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");
            return PclZip::errorCode();
          }
          $v_result_list[$p_options_list[$i]] = strtr($p_options_list[$i+1], '\\', '/');
          $i++;
        break;
        case PCLZIP_OPT_REMOVE_ALL_PATH :
          $v_result_list[$p_options_list[$i]] = true;
        break;
        case PCLZIP_OPT_SET_CHMOD :
          if (($i+1) >= $p_size) {
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");
            return PclZip::errorCode();
          }
          $v_result_list[$p_options_list[$i]] = $p_options_list[$i+1];
          $i++;
        break;
        case PCLZIP_CB_PRE_EXTRACT :
        case PCLZIP_CB_POST_EXTRACT :
        case PCLZIP_CB_PRE_ADD :
        case PCLZIP_CB_POST_ADD :
        if (($i+1) >= $p_size) {
            PclZip::privErrorLog(PCLZIP_ERR_MISSING_OPTION_VALUE, "Missing parameter value for option '".PclZipUtilOptionText($p_options_list[$i])."'");
            return PclZip::errorCode();
          }
          $v_function_name = $p_options_list[$i+1];
          if (!function_exists($v_function_name)) {
            PclZip::privErrorLog(PCLZIP_ERR_INVALID_OPTION_VALUE, "Function '".$v_function_name."()' is not an existing function for option '".PclZipUtilOptionText($p_options_list[$i])."'");
             return PclZip::errorCode();
          }
           $v_result_list[$p_options_list[$i]] = $v_function_name;
          $i++;
        break;
        default :
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Unknown optional parameter '".$p_options_list[$i]."'");
          return PclZip::errorCode();
      }
      $i++;
    }
     for ($key=reset($v_requested_options); $key=key($v_requested_options); $key=next($v_requested_options)) {
       if ($v_requested_options[$key] == 'mandatory') {
        if (!isset($v_result_list[$key])) {
          PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Missing mandatory parameter ".PclZipUtilOptionText($key)."(".$key.")");
           return PclZip::errorCode();
        }
      }
    }
    return $v_result;
  }

  function privOpenFd($p_mode)
  { $v_result=1;
    if ($this->zip_fd != 0)
    { PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Zip file \''.$this->zipname.'\' already open');
      return PclZip::errorCode();
    }
    if (($this->zip_fd = @fopen($this->zipname, $p_mode)) == 0)
    { PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in '.$p_mode.' mode');
      return PclZip::errorCode();
    }
    return $v_result;
  }

  function privCloseFd()
  { $v_result=1;
    if ($this->zip_fd != 0)
      @fclose($this->zip_fd);
    $this->zip_fd = 0;
    return $v_result;
  }

   function privConvertHeader2FileInfo($p_header, &$p_info)
  {  $v_result=1;
    $p_info['filename'] = $p_header['filename'];
    $p_info['stored_filename'] = $p_header['stored_filename'];
    $p_info['size'] = $p_header['size'];
    $p_info['compressed_size'] = $p_header['compressed_size'];
    $p_info['mtime'] = $p_header['mtime'];
    $p_info['comment'] = $p_header['comment'];
    $p_info['folder'] = ($p_header['external']==0x41FF0010);
    $p_info['index'] = $p_header['index'];
    $p_info['status'] = $p_header['status'];
    return $v_result;
  }

  function privExtract(&$p_file_list, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  { $v_result=1;
    if (($p_path == "") || ((substr($p_path, 0, 1) != "/") && (substr($p_path, 0, 3) != "../")))
      $p_path = "./".$p_path;
    if (($p_path != "./") && ($p_path != "/"))
    { while (substr($p_path, -1) == "/")
      { $p_path = substr($p_path, 0, strlen($p_path)-1);
      }
    }
    if (($p_remove_path != "") && (substr($p_remove_path, -1) != '/'))
    { $p_remove_path .= '/';
    }
    $p_remove_path_size = strlen($p_remove_path);
    if (($v_result = $this->privOpenFd('rb')) != 1)
    { return $v_result;
    }
    $v_central_dir = array();
    if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
    { $this->privCloseFd();
      return $v_result;
    }
     $v_pos_entry = $v_central_dir['offset'];
     for ($i=0; $i<$v_central_dir['entries']; $i++)
    { @rewind($this->zip_fd);
      if (@fseek($this->zip_fd, $v_pos_entry))
      { $this->privCloseFd();
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');
        return PclZip::errorCode();
      } $v_header = array();
      if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1)
      { $this->privCloseFd();
        return $v_result;
      }
        $v_header['index'] = $i;
      $v_pos_entry = ftell($this->zip_fd);
      @rewind($this->zip_fd);
      if (@fseek($this->zip_fd, $v_header['offset']))
      { $this->privCloseFd();
        PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');
        return PclZip::errorCode();
      }
      if (($v_result = $this->privExtractFile($v_header, $p_path, $p_remove_path, $p_remove_all_path, $p_options)) != 1)
      { $this->privCloseFd();
        return $v_result;
      }
      if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$i])) != 1)
      { $this->privCloseFd();
        return $v_result;
      }
    }
    $this->privCloseFd();
    return $v_result;
  }

 function privExtractFile(&$p_entry, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  { $v_result=1;
    if (($v_result = $this->privReadFileHeader($v_header)) != 1)
    { return $v_result;
    }
    if ($p_remove_all_path == true) {
        $p_entry['filename'] = basename($p_entry['filename']);
    }
     else if ($p_remove_path != "")
    { if (PclZipUtilPathInclusion($p_remove_path, $p_entry['filename']) == 2)
      { $p_entry['status'] = "filtered";
        return $v_result;
      }
      $p_remove_path_size = strlen($p_remove_path);
      if (substr($p_entry['filename'], 0, $p_remove_path_size) == $p_remove_path)
      { $p_entry['filename'] = substr($p_entry['filename'], $p_remove_path_size);
      }
    }
    if ($p_path != '')
    { $p_entry['filename'] = $p_path."/".$p_entry['filename'];
    }
    if (isset($p_options[PCLZIP_CB_PRE_EXTRACT])) {
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
      eval('$v_result = '.$p_options[PCLZIP_CB_PRE_EXTRACT].'(PCLZIP_CB_PRE_EXTRACT, $v_local_header);');
      if ($v_result == 0) {
        $p_entry['status'] = "skipped";
      }
      $p_entry['filename'] = $v_local_header['filename'];
    }
    if ($p_entry['status'] == 'ok') {
    if (file_exists($p_entry['filename']))
    { if (is_dir($p_entry['filename']))
      { $p_entry['status'] = "already_a_directory";
      }
      else if (!is_writeable($p_entry['filename']))
      { $p_entry['status'] = "write_protected";
      }
      else if (filemtime($p_entry['filename']) > $p_entry['mtime'])
      { $p_entry['status'] = "newer_exist";
      }
    }
    else {
      if (substr($p_entry['filename'], -1) == '/')
        $v_dir_to_check = $p_entry['filename'];
      else if (!strstr($p_entry['filename'], "/"))
        $v_dir_to_check = "";
      else
        $v_dir_to_check = dirname($p_entry['filename']);
      if (($v_result = $this->privDirCheck($v_dir_to_check, ($p_entry['external']==0x41FF0010))) != 1) {
        $p_entry['status'] = "path_creation_fail";
        $v_result = 1;
      }
    }
    }
    if ($p_entry['status'] == 'ok') {
    if (!($p_entry['external']==0x41FF0010))
    { if ($p_entry['compressed_size'] == $p_entry['size'])
      { if (($v_dest_file = @fopen($p_entry['filename'], 'wb')) == 0)
        { $p_entry['status'] = "write_error";
          return $v_result;
        }
        $v_size = $p_entry['compressed_size'];
        while ($v_size != 0)
        { $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
          $v_buffer = fread($this->zip_fd, $v_read_size);
          $v_binary_data = pack('a'.$v_read_size, $v_buffer);
          @fwrite($v_dest_file, $v_binary_data, $v_read_size);
          $v_size -= $v_read_size;
        }
        fclose($v_dest_file);
        touch($p_entry['filename'], $p_entry['mtime']);
      }
      else
      { if (($v_dest_file = @fopen($p_entry['filename'].'.gz', 'wb')) == 0)
        { $p_entry['status'] = "write_error";
          return $v_result;
        }
        $v_binary_data = pack('va1a1Va1a1', 0x8b1f, Chr($p_entry['compression']), Chr(0x00), time(), Chr(0x00), Chr(3));
        fwrite($v_dest_file, $v_binary_data, 10);
        $v_size = $p_entry['compressed_size'];
        while ($v_size != 0)
        { $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
          $v_buffer = fread($this->zip_fd, $v_read_size);
          $v_binary_data = pack('a'.$v_read_size, $v_buffer);
          @fwrite($v_dest_file, $v_binary_data, $v_read_size);
          $v_size -= $v_read_size;
        }
        $v_binary_data = pack('VV', $p_entry['crc'], $p_entry['size']);
        fwrite($v_dest_file, $v_binary_data, 8);
        fclose($v_dest_file);
        if (($v_src_file = gzopen($p_entry['filename'].'.gz', 'rb')) == 0)
        { $p_entry['status'] = "read_error";
          return $v_result;
        }
        if (($v_dest_file = @fopen($p_entry['filename'], 'wb')) == 0)
        { $p_entry['status'] = "write_error";
          gzclose($v_src_file);
          return $v_result;
        }
        $v_size = $p_entry['size'];
        while ($v_size != 0)
        {  $v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
           $v_buffer = gzread($v_src_file, $v_read_size);
          $v_binary_data = pack('a'.$v_read_size, $v_buffer);
          @fwrite($v_dest_file, $v_binary_data, $v_read_size);
          $v_size -= $v_read_size;
        }
        fclose($v_dest_file);
        gzclose($v_src_file);
        touch($p_entry['filename'], $p_entry['mtime']);
        @unlink($p_entry['filename'].'.gz');
      }
      if (isset($p_options[PCLZIP_OPT_SET_CHMOD])) {
        chmod($p_entry['filename'], $p_options[PCLZIP_OPT_SET_CHMOD]);
      }
     }
    }
    if (isset($p_options[PCLZIP_CB_POST_EXTRACT])) {
      $v_local_header = array();
      $this->privConvertHeader2FileInfo($p_entry, $v_local_header);
      eval('$v_result = '.$p_options[PCLZIP_CB_POST_EXTRACT].'(PCLZIP_CB_POST_EXTRACT, $v_local_header);');
    }
    return $v_result;
  }

  function privReadFileHeader(&$p_header)
  { $v_result=1;
    $v_binary_data = @fread($this->zip_fd, 4);
    $v_data = unpack('Vid', $v_binary_data);
    if ($v_data['id'] != 0x04034b50)
    { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Invalid archive structure');
      return PclZip::errorCode();
    }
    $v_binary_data = fread($this->zip_fd, 26);
    if (strlen($v_binary_data) != 26)
    {  $p_header['filename'] = "";
      $p_header['status'] = "invalid_header";
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid block size : ".strlen($v_binary_data));
      return PclZip::errorCode();
    }
    $v_data = unpack('vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $v_binary_data);
    $p_header['filename'] = fread($this->zip_fd, $v_data['filename_len']);
    if ($v_data['extra_len'] != 0) {
      $p_header['extra'] = fread($this->zip_fd, $v_data['extra_len']);
    }
    else {
      $p_header['extra'] = '';
    }
    $p_header['compression'] = $v_data['compression'];
    $p_header['size'] = $v_data['size'];
    $p_header['compressed_size'] = $v_data['compressed_size'];
    $p_header['crc'] = $v_data['crc'];
    $p_header['flag'] = $v_data['flag'];
    $p_header['mdate'] = $v_data['mdate'];
    $p_header['mtime'] = $v_data['mtime'];
    if ($p_header['mdate'] && $p_header['mtime'])
    { $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;
      $p_header['mtime'] = mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);
      }
    else
    { $p_header['mtime'] = time();
    }
    $p_header['stored_filename'] = $p_header['filename'];
    $p_header['status'] = "ok";
    return $v_result;
  }

  function privReadCentralFileHeader(&$p_header)
  { $v_result=1;
    $v_binary_data = @fread($this->zip_fd, 4);
    $v_data = unpack('Vid', $v_binary_data);
    if ($v_data['id'] != 0x02014b50)
    { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Invalid archive structure');
      return PclZip::errorCode();
    }
    $v_binary_data = fread($this->zip_fd, 42);
    if (strlen($v_binary_data) != 42)
    { $p_header['filename'] = "";
      $p_header['status'] = "invalid_header";
      PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid block size : ".strlen($v_binary_data));
      return PclZip::errorCode();
    }
    $p_header = unpack('vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $v_binary_data);
    if ($p_header['filename_len'] != 0)
      $p_header['filename'] = fread($this->zip_fd, $p_header['filename_len']);
    else
      $p_header['filename'] = '';
    if ($p_header['extra_len'] != 0)
      $p_header['extra'] = fread($this->zip_fd, $p_header['extra_len']);
    else
      $p_header['extra'] = '';
    if ($p_header['comment_len'] != 0)
      $p_header['comment'] = fread($this->zip_fd, $p_header['comment_len']);
    else
      $p_header['comment'] = '';
    if ($p_header['mdate'] && $p_header['mtime'])
    { $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;
      $p_header['mtime'] = mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);
    }
    else
    {  $p_header['mtime'] = time();
    }
    $p_header['stored_filename'] = $p_header['filename'];
    $p_header['status'] = 'ok';
    if (substr($p_header['filename'], -1) == '/')
    { $p_header['external'] = 0x41FF0010;
    }
    return $v_result;
  }

  function privReadEndCentralDir(&$p_central_dir)
  { $v_result=1;
    $v_size = filesize($this->zipname);
    @fseek($this->zip_fd, $v_size);
     if (@ftell($this->zip_fd) != $v_size)
    { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to go to the end of the archive \''.$this->zipname.'\'');
      return PclZip::errorCode();
    }
    $v_found = 0;
    if ($v_size > 26) {
      @fseek($this->zip_fd, $v_size-22);
      if (($v_pos = @ftell($this->zip_fd)) != ($v_size-22))
      { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');
        return PclZip::errorCode();
      }
      $v_binary_data = @fread($this->zip_fd, 4);
      $v_data = unpack('Vid', $v_binary_data);
      if ($v_data['id'] == 0x06054b50) {
        $v_found = 1;
      }
      $v_pos = ftell($this->zip_fd);
    }
    if (!$v_found) {
      $v_maximum_size = 65557; // 0xFFFF + 22;
      if ($v_maximum_size > $v_size)
        $v_maximum_size = $v_size;
      @fseek($this->zip_fd, $v_size-$v_maximum_size);
      if (@ftell($this->zip_fd) != ($v_size-$v_maximum_size))
      { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');
        return PclZip::errorCode();
      }
      $v_pos = ftell($this->zip_fd);
      $v_bytes = 0x00000000;
      while ($v_pos < $v_size)
      { $v_byte = @fread($this->zip_fd, 1);
        $v_bytes = ($v_bytes << 8) | Ord($v_byte);
        if ($v_bytes == 0x504b0506)
        { $v_pos++;
          break;
        }
        $v_pos++;
      }
      if ($v_pos == $v_size)
      { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Unable to find End of Central Dir Record signature");
        return PclZip::errorCode();
      }
    }
   $v_binary_data = fread($this->zip_fd, 18);
   if (strlen($v_binary_data) != 18)
    { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));
      return PclZip::errorCode();
    }
    $v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);
    if (($v_pos + $v_data['comment_size'] + 18) != $v_size)
    { PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Fail to find the right signature");
      return PclZip::errorCode();
    }
    if ($v_data['comment_size'] != 0)
      $p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
    else
      $p_central_dir['comment'] = '';
    $p_central_dir['entries'] = $v_data['entries'];
    $p_central_dir['disk_entries'] = $v_data['disk_entries'];
    $p_central_dir['offset'] = $v_data['offset'];
    $p_central_dir['size'] = $v_data['size'];
    $p_central_dir['disk'] = $v_data['disk'];
    $p_central_dir['disk_start'] = $v_data['disk_start'];
    return $v_result;
  }

  function privDirCheck($p_dir, $p_is_dir=false)
  { $v_result = 1;
    if (($p_is_dir) && (substr($p_dir, -1)=='/'))
    { $p_dir = substr($p_dir, 0, strlen($p_dir)-1);
    }
    if ((is_dir($p_dir)) || ($p_dir == ""))
    { return 1;
    }
    $p_parent_dir = dirname($p_dir);
    if ($p_parent_dir != $p_dir)
    { if ($p_parent_dir != "")
      {
        if (($v_result = $this->privDirCheck($p_parent_dir)) != 1)
        { return $v_result;
        }
      }
    }
    if (!@mkdir($p_dir, 0777))
    { PclZip::privErrorLog(PCLZIP_ERR_DIR_CREATE_FAIL, "Unable to create directory '$p_dir'");
      return PclZip::errorCode();
    }
    return $v_result;
  }

 function privErrorLog($p_error_code=0, $p_error_string='')
  { if (PCLZIP_ERROR_EXTERNAL == 1) {
      PclError($p_error_code, $p_error_string);
    }
    else {
      $this->error_code = $p_error_code;
      $this->error_string = $p_error_string;
    }
  }

  function privErrorReset()
  { if (PCLZIP_ERROR_EXTERNAL == 1) {
      PclErrorReset();
    }
    else {
      $this->error_code = 1;
      $this->error_string = '';
    }
  }
}