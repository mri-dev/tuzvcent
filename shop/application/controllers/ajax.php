<?
use ShopManager\Cart;
use Applications\Cetelem;
use PortalManager\CasadaShop;
use PopupManager\Creative;
use PopupManager\CreativeScreens;

class ajax extends Controller{
		function __construct()
		{
			header("Access-Control-Allow-Origin: *");

			parent::__construct();
		}

		function post(){
			extract($_POST);
			$ret = array(
				'success' => 0,
				'msg' => false
			);
			switch($type)
			{

				case 'cetelemCalculator':

					$ret['data'] 	= false;
					$ret['show'] 	= false;
					$data 			= array();
					$ret['price'] 	= $price;
					$ret['ownPrice']= $ownPrice;

					// Cetelem API
					$cetelem = new Cetelem(
						$this->view->settings['cetelem_shopcode'],
						$this->view->settings['cetelem_society'],
						$this->view->settings['cetelem_barem'],
						array( 'db' => $this->db )
					);


					$cetelem->sandboxMode( CETELEM_SANDBOX_MODE );


					$data = $cetelem->calc($price, $ownPrice);


					$ret[data] = $data;


				break;

				case 'logPopupClick':

					$this->db->insert(
						'popup_clicks',
						array(
							'creative_id' => $creative,
							'screen_id' => $screen,
							'session_id' => $sessionid,
							'closed' => $closed
						)
					);
				break;
				case 'getPopupScreenVariables':

					$ret['data'] 	= false;
					$ret['show'] 	= false;
					$data 			= array();
					$go 			= true;

					if (isset($url)) {
						$ret['url'] = $url;
					}

					$creative = (new Creative(array('db'=> $this->db)))->loadByURI( $url );
					$creative_settings = $creative->getSettings();

					// Időpont megvizsgálása, hogy mikor látta a creatívot utoljára
					$last_view_delay = $creative->getSessionLastViewAsSec($sessionid);
					$ret['last_view_diff_insec'] = $last_view_delay;

					// Megjelenés korlátozás
					$viewed_numbers = (int)$creative->getSessionViewedNumbers($sessionid);
					$ret['viewed_numbers'] = $viewed_numbers;

					// Időeltelés vizsgálat
					if ( $last_view_delay <= $creative_settings[view_sec_btw] )
					{
						$go = false;
					}

					// Megjelenés vizsgálat
					if ( $viewed_numbers >= $creative_settings[view_max] )
					{
						$go = false;
					}

					if ($go && $creative->hasData())
					{
						$cr = array();

						$cr['id'] 		= $creative->getID();
						$cr['type'] 	= $creative->getType();
						$cr['settings'] = $creative_settings;

						$data['creative'] = $cr;

						$screen = (new CreativeScreens($cr['id'], array('db' => $this->db)))->loadForAction($sessionid);

						$data['screen'] 		= $screen;
						$data['screen_loaded'] 	= $screen[id];

						if (empty($screen)) {
							$data = array();
						}
					}


					if (!empty($data))
					{
						$ret['data'] 	= $data;
						$ret['success'] = 1;
						$ret['show'] 	= true;
					}

				break;

				case 'logPopupScreenshow':
					$ret[post] = $_POST;
					$creative = (new Creative(array('db'=> $this->db)))->load( $creative );
					$creative->logShow( $sessionid, $screen );
				break;

				case 'log':
					switch($mode){
						case 'searching':
							$this->shop->logSearching($val);
						break;
					}
				break;
				case 'cart':
					switch($mode){
						case 'add':
							$err = false;

							if(!$err && $t == '') $err = $this->escape('Hibás termék azonosító, próbálja meg később!',$ret);
							if(!$err && ($m == '' || $m == 0)) $err = $this->escape('Kérjük adja meg hogy hány terméket szeretne a kosárba helyezni!',$ret);

							try{
								$this->shop->addToCart(Helper::getMachineID(), $t, $m);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
							}

							if(!$err)
							$this->setSuccess('A terméket sikeresen a kosárba helyezte!',$ret);

							echo json_encode($ret);
							return;
						break;
						case 'remove':
							$err = false;
							if(!$err && $id == '') $err = $this->escape('Hibás termék azonosító, próbálja meg később!',$ret);

							try{
								$this->shop->removeFromCart(Helper::getMachineID(), $id);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
							}

							if(!$err)
							$this->setSuccess('A terméket sikeresen eltávolította a kosárból!',$ret);

							echo json_encode($ret);
							return;
						break;
						case 'addItem':
							$err = false;
							if(!$err && $id == '') $err = $this->escape('Hibás termék azonosító, próbálja meg később!',$ret);

							try{
								$this->shop->addItemToCart(Helper::getMachineID(), $id);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
							}

							if(!$err)
							$this->setSuccess('Sikeresen megnövelte a termék mennyiségét a kosárban!',$ret);

							echo json_encode($ret);
							return;
						break;
						case 'removeItem':
							$err = false;
							if(!$err && $id == '') $err = $this->escape('Hibás termék azonosító, próbálja meg később!',$ret);

							try{
								$this->shop->removeItemFromCart(Helper::getMachineID(), $id);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
							}

							if(!$err)
							$this->setSuccess('Sikeresen csökkentette a termék mennyiségét a kosárban!',$ret);

							echo json_encode($ret);
							return;
						break;
					}
				break;
				case 'user':
					switch($mode){
						case 'add':
							$err = false;
							try{
								$re = $this->User->add($_POST);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
								$ret[errorCode] = $e->getCode();
							}

							if(!$err)
							$this->setSuccess('Regisztráció sikeres! Kellemes vásárlást kívánunk!',$ret);

							echo json_encode($ret);
							return;
						break;
						case 'login':
							$err = false;
							try{
								$re = $this->User->login($_POST[data]);

								if( $re && $re[remember]){
									setcookie('ajx_login_usr', $re[email], time() + 60*60*24*3, '/' );
									setcookie('ajx_login_pw', $re[pw], time() + 60*60*24*3, '/' );
								}else{
									setcookie('ajx_login_usr', null, time() - 3600, '/' );
									setcookie('ajx_login_pw', null , time() -3600, '/' );
								}

							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
								$ret[errorCode] = $e->getCode();
							}

							if(!$err)
							$this->setSuccess('Sikeresen bejelentkezett!',$ret);

							echo json_encode($ret);
							return;
						break;
						case 'resetPassword':
							$err = false;
							try{
								$re = $this->User->resetPassword($_POST[data]);
							}catch(Exception $e){
								$err = $this->escape($e->getMessage(),$ret);
								$ret[errorCode] = $e->getCode();
							}

							if(!$err)
							$this->setSuccess('Új jelszó sikeresen generálva!',$ret);

							echo json_encode($ret);
							return;
						break;
					}
				break;
			}
			echo json_encode($ret);
		}

		private function setSuccess($msg, &$ret){
			$ret[msg] 		= $msg;
			$ret[success] 	= 1;
			return true;
		}
		private function escape($msg, &$ret){
			$ret[msg] 		= $msg;
			$ret[success] 	= 0;
			return true;
		}

		function update () {

			switch ( $this->view->gets[2] ) {
				// Pick Pack Pontok listájának frissítése
				// {DOMAIN}/ajax/update/updatePickPackPont
				/*
				case 'updatePickPackPont':
					$this->model->openLib('PickPackPont',array(
						'database' => $this->model->db,
						'update' => true
					));
				break;
				*/
			}
		}

		function get(){
			extract($_POST);

			switch($type){
				case 'cartInfo':
					$mid 	= Helper::getMachineID();
					$cart 	= new Cart($mid, array( 'db' => $this->db, 'user' => $this->User->get(), 'settings' => $this->view->settings ));
					echo json_encode($cart->get());
				break;

				case 'pickpackpont':
					$this->ppp = $this->model->openLib('PickPackPont',array(
						'database' => $this->model->db
					));

					$this->pickpack->data 	= $this->ppp->getList();
					switch($mode){
						case 'getCities':
							$this->pickpack->varosok 	= $this->ppp->getCities($this->pickpack->data);
							$data = $this->pickpack->varosok[$arg[megye]];
							echo json_encode($data);
						break;
						case 'getPoints':
							$this->pickpack->uzletek 	= $this->ppp->getPoints($this->pickpack->data);
							$data = $this->pickpack->uzletek[$arg[megye]][$arg[varos]];
							echo json_encode($data);
						break;
						case 'getPointData':
							$data = $this->ppp->getPointData($arg[id]);
							echo json_encode($data);
						break;
					}
				break;
			}

			$this->view->render(__CLASS__.'/'.__FUNCTION__.'/'.$type, true);
		}

		function box(){
			extract($_POST);

			switch($type){
				case 'recall':
					$this->view->t = $this->shop->getTermekAdat($tid);
				break;
				case 'askForTermek':
					$this->view->t = $this->shop->getTermekAdat($tid);
				break;
				case 'map':
					$shop = new CasadaShop( (int)$tid, array(
						'db' => $this->db
					));

					$this->out('shop',$shop);
				break;
			}

			$this->view->render(__CLASS__.'/'.__FUNCTION__.'/'.$type, true);
		}

		function __destruct(){
		}
	}

?>
