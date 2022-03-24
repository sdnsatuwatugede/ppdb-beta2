<?php 
/**
 * Data_siswa Page Controller
 * @category  Controller
 */
class Data_siswaController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "data_siswa";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("id", 
			"no_pendaftaran", 
			"tanggal_daftar", 
			"username", 
			"nama_siswa", 
			"jenis_kelamin", 
			"nik_siswa", 
			"tempat_lhr", 
			"tanggal_lhr", 
			"no_reg_akte", 
			"kewarga", 
			"Agama", 
			"keb_khusus_s", 
			"alamat", 
			"rt", 
			"rw", 
			"dusun", 
			"kelurahan", 
			"kec", 
			"kd_pos", 
			"tmp_tg", 
			"moda_trans", 
			"anak_ke", 
			"nama_ayah", 
			"nik_ayah", 
			"th_lhr_ayah", 
			"pendidikan_ayah", 
			"pekerjaan_ayah", 
			"penghasilan_ay", 
			"nama_ibu", 
			"nik_ibu", 
			"th_lhr_ibu", 
			"pendidikan_ibu", 
			"penghasilan_ibu", 
			"pekerjaan_ibu", 
			"no_telp", 
			"no_hp", 
			"tinggi_badan", 
			"berat_badan", 
			"jarak_rumah", 
			"waktu_tempuh", 
			"asal_sekolah");
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				data_siswa.id LIKE ? OR 
				data_siswa.no_pendaftaran LIKE ? OR 
				data_siswa.tanggal_daftar LIKE ? OR 
				data_siswa.username LIKE ? OR 
				data_siswa.nama_siswa LIKE ? OR 
				data_siswa.jenis_kelamin LIKE ? OR 
				data_siswa.nik_siswa LIKE ? OR 
				data_siswa.tempat_lhr LIKE ? OR 
				data_siswa.tanggal_lhr LIKE ? OR 
				data_siswa.no_reg_akte LIKE ? OR 
				data_siswa.kewarga LIKE ? OR 
				data_siswa.Agama LIKE ? OR 
				data_siswa.keb_khusus_s LIKE ? OR 
				data_siswa.alamat LIKE ? OR 
				data_siswa.rt LIKE ? OR 
				data_siswa.rw LIKE ? OR 
				data_siswa.dusun LIKE ? OR 
				data_siswa.kelurahan LIKE ? OR 
				data_siswa.kec LIKE ? OR 
				data_siswa.kd_pos LIKE ? OR 
				data_siswa.tmp_tg LIKE ? OR 
				data_siswa.moda_trans LIKE ? OR 
				data_siswa.anak_ke LIKE ? OR 
				data_siswa.nama_ayah LIKE ? OR 
				data_siswa.nik_ayah LIKE ? OR 
				data_siswa.th_lhr_ayah LIKE ? OR 
				data_siswa.pendidikan_ayah LIKE ? OR 
				data_siswa.pekerjaan_ayah LIKE ? OR 
				data_siswa.penghasilan_ay LIKE ? OR 
				data_siswa.nama_ibu LIKE ? OR 
				data_siswa.nik_ibu LIKE ? OR 
				data_siswa.th_lhr_ibu LIKE ? OR 
				data_siswa.pendidikan_ibu LIKE ? OR 
				data_siswa.penghasilan_ibu LIKE ? OR 
				data_siswa.pekerjaan_ibu LIKE ? OR 
				data_siswa.no_telp LIKE ? OR 
				data_siswa.no_hp LIKE ? OR 
				data_siswa.tinggi_badan LIKE ? OR 
				data_siswa.berat_badan LIKE ? OR 
				data_siswa.jarak_rumah LIKE ? OR 
				data_siswa.waktu_tempuh LIKE ? OR 
				data_siswa.asal_sekolah LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "data_siswa/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("data_siswa.id", ORDER_TYPE);
		}
		$allowed_roles = array ('admin', 'guru');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("data_siswa.username", get_active_user('nama_user') );
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Data Siswa";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("data_siswa/list.php", $data); //render the full page
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("id", 
			"no_pendaftaran", 
			"tanggal_daftar", 
			"username", 
			"nama_siswa", 
			"jenis_kelamin", 
			"nik_siswa", 
			"tempat_lhr", 
			"tanggal_lhr", 
			"no_reg_akte", 
			"kewarga", 
			"Agama", 
			"keb_khusus_s", 
			"alamat", 
			"rt", 
			"rw", 
			"dusun", 
			"kelurahan", 
			"kec", 
			"kd_pos", 
			"tmp_tg", 
			"moda_trans", 
			"anak_ke", 
			"nama_ayah", 
			"nik_ayah", 
			"th_lhr_ayah", 
			"pendidikan_ayah", 
			"pekerjaan_ayah", 
			"penghasilan_ay", 
			"nama_ibu", 
			"nik_ibu", 
			"th_lhr_ibu", 
			"pendidikan_ibu", 
			"penghasilan_ibu", 
			"pekerjaan_ibu", 
			"no_telp", 
			"no_hp", 
			"tinggi_badan", 
			"berat_badan", 
			"jarak_rumah", 
			"waktu_tempuh", 
			"asal_sekolah");
		$allowed_roles = array ('admin', 'guru');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("data_siswa.username", get_active_user('nama_user') );
		}
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("data_siswa.id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  Data Siswa";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("data_siswa/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("no_pendaftaran","tanggal_daftar","username","nama_siswa","jenis_kelamin","nik_siswa","tempat_lhr","tanggal_lhr","no_reg_akte","kewarga","Agama","keb_khusus_s","alamat","rt","rw","dusun","kelurahan","kec","kd_pos","tmp_tg","moda_trans","anak_ke","nama_ayah","nik_ayah","th_lhr_ayah","pendidikan_ayah","pekerjaan_ayah","penghasilan_ay","nama_ibu","nik_ibu","th_lhr_ibu","pendidikan_ibu","pekerjaan_ibu","penghasilan_ibu","no_telp","no_hp","tinggi_badan","berat_badan","jarak_rumah","waktu_tempuh","asal_sekolah");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'no_pendaftaran' => 'required',
				'tanggal_daftar' => 'required',
				'username' => 'required',
				'nama_siswa' => 'required',
				'jenis_kelamin' => 'required',
				'nik_siswa' => 'required|numeric|min_numeric,1111111111111111',
				'tempat_lhr' => 'required',
				'tanggal_lhr' => 'required',
				'no_reg_akte' => 'required',
				'kewarga' => 'required',
				'Agama' => 'required',
				'keb_khusus_s' => 'required',
				'alamat' => 'required',
				'rt' => 'required|numeric',
				'rw' => 'required|numeric',
				'dusun' => 'required',
				'kelurahan' => 'required',
				'kec' => 'required',
				'kd_pos' => 'required|numeric',
				'tmp_tg' => 'required',
				'moda_trans' => 'required',
				'anak_ke' => 'required|numeric',
				'nama_ayah' => 'required',
				'nik_ayah' => 'required|numeric|min_numeric,1111111111111111',
				'th_lhr_ayah' => 'required',
				'pendidikan_ayah' => 'required',
				'pekerjaan_ayah' => 'required',
				'penghasilan_ay' => 'required',
				'nama_ibu' => 'required',
				'nik_ibu' => 'required|numeric|min_numeric,1111111111111111',
				'th_lhr_ibu' => 'required',
				'pendidikan_ibu' => 'required',
				'pekerjaan_ibu' => 'required',
				'penghasilan_ibu' => 'required',
				'no_telp' => 'required',
				'no_hp' => 'required',
				'tinggi_badan' => 'required|numeric',
				'berat_badan' => 'required|numeric',
				'jarak_rumah' => 'required',
				'waktu_tempuh' => 'required',
				'asal_sekolah' => 'required',
			);
			$this->sanitize_array = array(
				'no_pendaftaran' => 'sanitize_string',
				'tanggal_daftar' => 'sanitize_string',
				'username' => 'sanitize_string',
				'nama_siswa' => 'sanitize_string',
				'jenis_kelamin' => 'sanitize_string',
				'nik_siswa' => 'sanitize_string',
				'tempat_lhr' => 'sanitize_string',
				'tanggal_lhr' => 'sanitize_string',
				'no_reg_akte' => 'sanitize_string',
				'kewarga' => 'sanitize_string',
				'Agama' => 'sanitize_string',
				'keb_khusus_s' => 'sanitize_string',
				'alamat' => 'sanitize_string',
				'rt' => 'sanitize_string',
				'rw' => 'sanitize_string',
				'dusun' => 'sanitize_string',
				'kelurahan' => 'sanitize_string',
				'kec' => 'sanitize_string',
				'kd_pos' => 'sanitize_string',
				'tmp_tg' => 'sanitize_string',
				'moda_trans' => 'sanitize_string',
				'anak_ke' => 'sanitize_string',
				'nama_ayah' => 'sanitize_string',
				'nik_ayah' => 'sanitize_string',
				'th_lhr_ayah' => 'sanitize_string',
				'pendidikan_ayah' => 'sanitize_string',
				'pekerjaan_ayah' => 'sanitize_string',
				'penghasilan_ay' => 'sanitize_string',
				'nama_ibu' => 'sanitize_string',
				'nik_ibu' => 'sanitize_string',
				'th_lhr_ibu' => 'sanitize_string',
				'pendidikan_ibu' => 'sanitize_string',
				'pekerjaan_ibu' => 'sanitize_string',
				'penghasilan_ibu' => 'sanitize_string',
				'no_telp' => 'sanitize_string',
				'no_hp' => 'sanitize_string',
				'tinggi_badan' => 'sanitize_string',
				'berat_badan' => 'sanitize_string',
				'jarak_rumah' => 'sanitize_string',
				'waktu_tempuh' => 'sanitize_string',
				'asal_sekolah' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Selamat Data Sudah Tersimpan", "success");
					return	$this->redirect("data_siswa/view/$rec_id");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Tambah Data Siswa";
		$this->render_view("data_siswa/add.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id","no_pendaftaran","tanggal_daftar","username","nama_siswa","jenis_kelamin","nik_siswa","tempat_lhr","tanggal_lhr","no_reg_akte","kewarga","Agama","keb_khusus_s","alamat","rt","rw","dusun","kelurahan","kec","kd_pos","tmp_tg","moda_trans","anak_ke","nama_ayah","nik_ayah","th_lhr_ayah","pendidikan_ayah","pekerjaan_ayah","penghasilan_ay","nama_ibu","nik_ibu","th_lhr_ibu","pendidikan_ibu","pekerjaan_ibu","penghasilan_ibu","no_telp","no_hp","tinggi_badan","berat_badan","jarak_rumah","waktu_tempuh","asal_sekolah");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'no_pendaftaran' => 'required',
				'tanggal_daftar' => 'required',
				'username' => 'required',
				'nama_siswa' => 'required',
				'jenis_kelamin' => 'required',
				'nik_siswa' => 'required|numeric|min_numeric,1111111111111111',
				'tempat_lhr' => 'required',
				'tanggal_lhr' => 'required',
				'no_reg_akte' => 'required',
				'kewarga' => 'required',
				'Agama' => 'required',
				'keb_khusus_s' => 'required',
				'alamat' => 'required',
				'rt' => 'required|numeric',
				'rw' => 'required|numeric',
				'dusun' => 'required',
				'kelurahan' => 'required',
				'kec' => 'required',
				'kd_pos' => 'required|numeric',
				'tmp_tg' => 'required',
				'moda_trans' => 'required',
				'anak_ke' => 'required|numeric',
				'nama_ayah' => 'required',
				'nik_ayah' => 'required|numeric|min_numeric,1111111111111111',
				'th_lhr_ayah' => 'required',
				'pendidikan_ayah' => 'required',
				'pekerjaan_ayah' => 'required',
				'penghasilan_ay' => 'required',
				'nama_ibu' => 'required',
				'nik_ibu' => 'required|numeric|min_numeric,1111111111111111',
				'th_lhr_ibu' => 'required',
				'pendidikan_ibu' => 'required',
				'pekerjaan_ibu' => 'required',
				'penghasilan_ibu' => 'required',
				'no_telp' => 'required',
				'no_hp' => 'required',
				'tinggi_badan' => 'required|numeric',
				'berat_badan' => 'required|numeric',
				'jarak_rumah' => 'required',
				'waktu_tempuh' => 'required',
				'asal_sekolah' => 'required',
			);
			$this->sanitize_array = array(
				'no_pendaftaran' => 'sanitize_string',
				'tanggal_daftar' => 'sanitize_string',
				'username' => 'sanitize_string',
				'nama_siswa' => 'sanitize_string',
				'jenis_kelamin' => 'sanitize_string',
				'nik_siswa' => 'sanitize_string',
				'tempat_lhr' => 'sanitize_string',
				'tanggal_lhr' => 'sanitize_string',
				'no_reg_akte' => 'sanitize_string',
				'kewarga' => 'sanitize_string',
				'Agama' => 'sanitize_string',
				'keb_khusus_s' => 'sanitize_string',
				'alamat' => 'sanitize_string',
				'rt' => 'sanitize_string',
				'rw' => 'sanitize_string',
				'dusun' => 'sanitize_string',
				'kelurahan' => 'sanitize_string',
				'kec' => 'sanitize_string',
				'kd_pos' => 'sanitize_string',
				'tmp_tg' => 'sanitize_string',
				'moda_trans' => 'sanitize_string',
				'anak_ke' => 'sanitize_string',
				'nama_ayah' => 'sanitize_string',
				'nik_ayah' => 'sanitize_string',
				'th_lhr_ayah' => 'sanitize_string',
				'pendidikan_ayah' => 'sanitize_string',
				'pekerjaan_ayah' => 'sanitize_string',
				'penghasilan_ay' => 'sanitize_string',
				'nama_ibu' => 'sanitize_string',
				'nik_ibu' => 'sanitize_string',
				'th_lhr_ibu' => 'sanitize_string',
				'pendidikan_ibu' => 'sanitize_string',
				'pekerjaan_ibu' => 'sanitize_string',
				'penghasilan_ibu' => 'sanitize_string',
				'no_telp' => 'sanitize_string',
				'no_hp' => 'sanitize_string',
				'tinggi_badan' => 'sanitize_string',
				'berat_badan' => 'sanitize_string',
				'jarak_rumah' => 'sanitize_string',
				'waktu_tempuh' => 'sanitize_string',
				'asal_sekolah' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
		$allowed_roles = array ('admin', 'guru');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("data_siswa.username", get_active_user('nama_user') );
		}
				$db->where("data_siswa.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Record updated successfully", "success");
					return $this->redirect("data_siswa");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("data_siswa");
					}
				}
			}
		}
		$allowed_roles = array ('admin', 'guru');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("data_siswa.username", get_active_user('nama_user') );
		}
		$db->where("data_siswa.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Data Siswa";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("data_siswa/edit.php", $data);
	}
	/**
     * Update single field
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function editfield($rec_id = null, $formdata = null){
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("id","no_pendaftaran","tanggal_daftar","username","nama_siswa","jenis_kelamin","nik_siswa","tempat_lhr","tanggal_lhr","no_reg_akte","kewarga","Agama","keb_khusus_s","alamat","rt","rw","dusun","kelurahan","kec","kd_pos","tmp_tg","moda_trans","anak_ke","nama_ayah","nik_ayah","th_lhr_ayah","pendidikan_ayah","pekerjaan_ayah","penghasilan_ay","nama_ibu","nik_ibu","th_lhr_ibu","pendidikan_ibu","pekerjaan_ibu","penghasilan_ibu","no_telp","no_hp","tinggi_badan","berat_badan","jarak_rumah","waktu_tempuh","asal_sekolah");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'no_pendaftaran' => 'required',
				'tanggal_daftar' => 'required',
				'username' => 'required',
				'nama_siswa' => 'required',
				'jenis_kelamin' => 'required',
				'nik_siswa' => 'required|numeric|min_numeric,1111111111111111',
				'tempat_lhr' => 'required',
				'tanggal_lhr' => 'required',
				'no_reg_akte' => 'required',
				'kewarga' => 'required',
				'Agama' => 'required',
				'keb_khusus_s' => 'required',
				'alamat' => 'required',
				'rt' => 'required|numeric',
				'rw' => 'required|numeric',
				'dusun' => 'required',
				'kelurahan' => 'required',
				'kec' => 'required',
				'kd_pos' => 'required|numeric',
				'tmp_tg' => 'required',
				'moda_trans' => 'required',
				'anak_ke' => 'required|numeric',
				'nama_ayah' => 'required',
				'nik_ayah' => 'required|numeric|min_numeric,1111111111111111',
				'th_lhr_ayah' => 'required',
				'pendidikan_ayah' => 'required',
				'pekerjaan_ayah' => 'required',
				'penghasilan_ay' => 'required',
				'nama_ibu' => 'required',
				'nik_ibu' => 'required|numeric|min_numeric,1111111111111111',
				'th_lhr_ibu' => 'required',
				'pendidikan_ibu' => 'required',
				'pekerjaan_ibu' => 'required',
				'penghasilan_ibu' => 'required',
				'no_telp' => 'required',
				'no_hp' => 'required',
				'tinggi_badan' => 'required|numeric',
				'berat_badan' => 'required|numeric',
				'jarak_rumah' => 'required',
				'waktu_tempuh' => 'required',
				'asal_sekolah' => 'required',
			);
			$this->sanitize_array = array(
				'no_pendaftaran' => 'sanitize_string',
				'tanggal_daftar' => 'sanitize_string',
				'username' => 'sanitize_string',
				'nama_siswa' => 'sanitize_string',
				'jenis_kelamin' => 'sanitize_string',
				'nik_siswa' => 'sanitize_string',
				'tempat_lhr' => 'sanitize_string',
				'tanggal_lhr' => 'sanitize_string',
				'no_reg_akte' => 'sanitize_string',
				'kewarga' => 'sanitize_string',
				'Agama' => 'sanitize_string',
				'keb_khusus_s' => 'sanitize_string',
				'alamat' => 'sanitize_string',
				'rt' => 'sanitize_string',
				'rw' => 'sanitize_string',
				'dusun' => 'sanitize_string',
				'kelurahan' => 'sanitize_string',
				'kec' => 'sanitize_string',
				'kd_pos' => 'sanitize_string',
				'tmp_tg' => 'sanitize_string',
				'moda_trans' => 'sanitize_string',
				'anak_ke' => 'sanitize_string',
				'nama_ayah' => 'sanitize_string',
				'nik_ayah' => 'sanitize_string',
				'th_lhr_ayah' => 'sanitize_string',
				'pendidikan_ayah' => 'sanitize_string',
				'pekerjaan_ayah' => 'sanitize_string',
				'penghasilan_ay' => 'sanitize_string',
				'nama_ibu' => 'sanitize_string',
				'nik_ibu' => 'sanitize_string',
				'th_lhr_ibu' => 'sanitize_string',
				'pendidikan_ibu' => 'sanitize_string',
				'pekerjaan_ibu' => 'sanitize_string',
				'penghasilan_ibu' => 'sanitize_string',
				'no_telp' => 'sanitize_string',
				'no_hp' => 'sanitize_string',
				'tinggi_badan' => 'sanitize_string',
				'berat_badan' => 'sanitize_string',
				'jarak_rumah' => 'sanitize_string',
				'waktu_tempuh' => 'sanitize_string',
				'asal_sekolah' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
		$allowed_roles = array ('admin', 'guru');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("data_siswa.username", get_active_user('nama_user') );
		}
				$db->where("data_siswa.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if($bool && $numRows){
					return render_json(
						array(
							'num_rows' =>$numRows,
							'rec_id' =>$rec_id,
						)
					);
				}
				else{
					if($db->getLastError()){
						$page_error = $db->getLastError();
					}
					elseif(!$numRows){
						$page_error = "No record updated";
					}
					render_error($page_error);
				}
			}
			else{
				render_error($this->view->page_error);
			}
		}
		return null;
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("data_siswa.id", $arr_rec_id, "in");
		$allowed_roles = array ('admin');
		if(!in_array(strtolower(USER_ROLE), $allowed_roles)){
		$db->where("data_siswa.username", get_active_user('nama_user') );
		}
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Record deleted successfully", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("data_siswa");
	}
}
