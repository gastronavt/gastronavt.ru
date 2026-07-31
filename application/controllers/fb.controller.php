<?php

class FbController extends Controller
{
	public function __construct($params  = array())
	{
		parent::__construct($params);
		
		$this->model = new Main();

		$this->model->getTitle();
	}
	
	public function index()
	{
		$this->view->generate(
			'main/feedback_view.php',
			$this->model->getData()
		);
	}
	
	public function c()
	{
		$orderName = $this->params[0];
		if($orderName){
			$this->view->generate(
				'main/feedback_view.php',
				[
					'orderName' => $orderName,
				]
			);
		} else {
			header('HTTP/1.1 200 OK');
			header('Location: '.NFfunctions::getSiteProtocol().$_SERVER['HTTP_HOST']);
			exit();
		}
	}
	
	public function feedback_create()
	{
		$rating = intval($this->params[0]);
		$orderBean = null;
		if($this->params[1]){
			$orderBean = BeanFactory::getBean('ordrs_orders')->retrieve_by_string_fields(array ('name' => $this->params[1]));
		}
		
		$feedbackBean = BeanFactory::newBean('fdbck_feedback');
		$feedbackBean->rating_c = '0'.$rating;
		if($rating > 4){
			$feedbackBean->status_c = '03';
		} else {
			$feedbackBean->status_c = '01';
		}
		$feedbackBean->lngng_landings_id_c = App::$current_landing->id;
		$feedbackBean->assigned_user_id = App::$current_landing->assigned_user_id;
		if($orderBean->id){
			$feedbackBean->ordrs_orders_id_c = $orderBean->id;
			
			$clientBean = NFfunctions::getParentBean($orderBean, 'clnts_clients');
		}
		$feedbackId = $feedbackBean->save();
		
		if(isset($clientBean)){
			NFfunctions::setParentBean($feedbackBean, $clientBean);
		}
		
		NFfunctions::addSecuritygroupInBean($feedbackBean);//чтобы отработали для ролевой модели
		
		echo json_encode(
			[
				'feedback_id' => $feedbackId,
			]
		);
	}
	
	public function feedback_add_review()
	{
		$feedback_id = $this->params[0];
		if($feedback_id){
			$review_text = $_POST['review_text'];
			
			$feedbackBean = BeanFactory::getBean('fdbck_feedback', $feedback_id);
			$feedbackBean->description = $review_text;
			$feedbackBean->save();
			
			echo json_encode(
				[
					'success' => 'ok',
				]
			);
		} else {
			echo json_encode(
				[
					'error' => 'не передан feedback_id',
				]
			);
		}
	}
	
}