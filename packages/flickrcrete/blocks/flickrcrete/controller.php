<?php       
	class FlickrcreteBlockController extends BlockController {
		
		var $pobj;
		
		protected $btDescription = "A block for displaying your flickr images";
		protected $btName = "Flickrcrete";
		protected $btTable = "btFlickrcrete";
		protected $btInterfaceWidth = "350";
		protected $btInterfaceHeight = "400";

		public function on_page_view() {
			if ($this->enable_lightbox) {
				$html = Loader::helper('html');				
				$bv = new BlockView();
				$bv->setBlockObject($this->getBlockObject());
				$this->addHeaderItem($html->css($bv->getBlockURL() . '/fancybox/jquery.fancybox-1.3.4.css'));
				$this->addHeaderItem($html->javascript($bv->getBlockURL() . '/fancybox/jquery.fancybox-1.3.4.pack.js'));
				//include to enable mousewheel scroll
				if($this->lightbox_mouse_scroll == '1'){
					$this->addHeaderItem($html->javascript($bv->getBlockURL() .'/fancybox/jquery.mousewheel-3.0.4.pack.js'));
				}
			}
		}

		//gets the flicker api url and reads the xml
		function flickr_api($method, $api_key, $params){
			if($method == 'photostream'){$method='flickr.people.getPublicPhotos';}
			if($method == 'set'){$method='flickr.photosets.getPhotos';}
			$flickrapi='https://api.flickr.com/services/rest/';
			$flickrapi.='?method='.$method;
			$flickrapi.='&api_key='.$api_key;
			if($params['flickr_email'] != null){$flickrapi.='&find_email='.$params['flickr_email'];}
			if($params['username'] != null){$flickrapi.='&username='.$params['username'];}
			if($params['user_id'] != null){$flickrapi.='&user_id='.$params['user_id'];}
			if($params['photoset_id'] != null){$flickrapi.='&photoset_id='.$params['photoset_id'];}
			if($params['per_page'] != null){$flickrapi.='&per_page='.$params['per_page'];}
			if($params['page'] != null){$flickrapi.='&page='.$params['page'];}
			if($params['extras'] != null){$flickrapi.='&extras='.$params['extras'];}//can be used to return a description
			
			return simplexml_load_file($flickrapi);
		}
		
		//creates the flickr farm url for the image
		function flickr_farm($farm, $server, $id, $secret, $size){
			if($size == '500'){$size = '';}
			if($size == '640'){$size = '_z';}
			$flickr_farm = 'https://farm'.$farm.'.static.flickr.com/'.$server.'/'.$id.'_'.$secret.''.$size.'.jpg';
			
			return $flickr_farm;
		}
		
		function get_current_url(){
			$currentPage = Page::getCurrentPage();
			Loader::helper('navigation');
			$url = NavigationHelper::getLinkToCollection($currentPage, true);
			$separator = "?";
			if (strpos($url,"?")!=false){
  			$separator = "&";}
			
			$current_url = $url.$separator;

			return $current_url;
		}
		
		public function view(){;
			//get current page
			if($_GET['fcp']){ $page=$_GET['fcp']; }
			else{$page='1';}
			
			$pkg = Package::getByHandle('flickrcrete');
			$api_key = $pkg->config('FLICKRCRETE_API_KEY');
			
			$params= array('user_id'=>$this->user_id, 'photoset_id'=>$this->photoset_id, 'per_page'=>$this->per_page, 'page'=>$page, 'extras'=>'description%2C+owner');
			$flickr= $this->flickr_api($this->method, $api_key, $params);
			$items = $flickr->children()->children();

			//pagination information
			$p = $flickr->children()->getname();
			$p = $flickr->$p;
			$pages = $p['pages'];
			$total = $p['total'];
			$owner = $p['owner'];

			//the array containing the image information
			$images = array();
		
			//this creates an array of needed variables
			foreach ($items as $item)
			{
				$title = $item['title'];
				$thumbnail_src = $this->flickr_farm($item['farm'], $item['server'], $item['id'], $item['secret'], '_q');
				if($item->description){
					$description = str_replace('"', '\'', $item->description);
					}
				$full_image_src = $this->flickr_farm($item['farm'], $item['server'], $item['id'], $item['secret'], $this->image_size);
				if($this->method == 'set'){$flickr_link = 'https://www.flickr.com/photos/'.$owner.'/'.$item['id'];}
				else{$flickr_link = 'https://www.flickr.com/photos/'.$this->user_id.'/'.$item['id'];}
				$images[]=array('title'=>$title,
								'description'=>$description, 
								'thumbnail_src'=>$thumbnail_src, 
								'full_image_src'=>$full_image_src, 
								'flickr_link'=>$flickr_link);
			}
			
			//Set error messages
			$invalidusername = t('Oops. It appears like you have entered an invalid flickr email address. Please make sure this email address is associated with the flickr account.');
			$nophotos = t('Oops. It appears that something has went wrong with your flickrcrete. There are no photos in your ').$this->method.t(' or your api key is invalid.');
			$nomethod = t('Oops. It appears that something has went wrong with your flickrcrete. There is no method selected.');
			$noapikey = t('Oops. It appears that something has went wrong with your flickrcrete. There is no api key.');
			if(count($items) < 1){$this->set('error', $nophotos);}
			if($this->user_id == null){$this->set('error', $invalidusername);}
			if($this->method == null){$this->set('error', $nomethod);}
			if($api_key == null){$this->set('error', $noapikey);}
			
			//Set variables for view
			$this->set('current_url', $this->get_current_url());
			$this->set('enable_lightbox', $this->enable_lightbox);
			$this->set('lightbox_effect', $this->lightbox_effect);
			$this->set('lightbox_title_position', $this->lightbox_title_position);
			$column_width = (100 / $this->display_columns).'%';
			$this->set('column_width', $column_width);
			$this->set('thumbnail_size', $this->thumbnail_size.'px');
			$linkback_size = $this->thumbnail_size - ($this->thumbnail_size/4);
			$this->set('linkback_size', $linkback_size.'px');
			$this->set('pages', $pages);
			$this->set('page', $page);
			$this->set('total', $total);
			$this->set('images', $images);
			$this->set('display_pagination', $this->display_pagination);
			$this->set('display_prev_next', $this->display_prev_next);
			$this->set('display_image_count', $this->display_image_count);
			$this->set('display_page_numbers', $this->display_page_numbers);
		}
		
		//Save the form to the database	
		function save($data) { 
				$args['flickr_email'] = isset($data['flickr_email']) ? $data['flickr_email'] : '';
				
				//set flickr userid
				$pkg = Package::getByHandle('flickrcrete');
				$api_key = $pkg->config('FLICKRCRETE_API_KEY');
				$flickr= $this->flickr_api('flickr.people.findByEmail', $api_key, array('flickr_email'=>$args['flickr_email']));
				$args['user_id'] = $flickr->user[nsid];
				if($flickr->err[code] == 1){$args['user_id'] = '';}
				else{$args['user_id'] = $flickr->user[nsid];}
				
				$args['display_pagination'] = isset($data['display_pagination']) ? 1 : 0;
				$args['display_prev_next'] = isset($data['display_prev_next']) ? 1 : 0;
				$args['display_image_count'] = isset($data['display_image_count']) ? 1 : 0;
				$args['display_page_numbers'] = isset($data['display_page_numbers']) ? 1 : 0;

				$args['photoset_id'] = isset($data['photoset_id']) ? $data['photoset_id'] : '';
				$args['method'] = isset($data['method']) ? $data['method'] : 'photostream';
				$args['per_page'] = (intval($data['per_page'])>=1 && intval($data['per_page'])<=30) ? intval($data['per_page']) : 15;
				$args['display_columns'] = (intval($data['display_columns'])>=1 && intval($data['display_columns'])<=99) ? intval($data['display_columns']) : 5;
				$args['enable_lightbox'] = isset($data['enable_lightbox']) ? 1 : 0;
				$args['lightbox_mouse_scroll'] = isset($data['lightbox_mouse_scroll']) ? 1 : 0;
				$args['lightbox_effect'] = isset($data['lightbox_effect']) ? $data['lightbox_effect'] : 'fade';
				$args['lightbox_title_position'] = isset($data['lightbox_title_position']) ? $data['lightbox_title_position'] : 'inside';
				$args['thumbnail_size'] = (intval($data['thumbnail_size'])>=1 && intval($data['thumbnail_size'])<=300) ? intval($data['thumbnail_size']) : 75;
				$args['image_size'] = isset($data['image_size']) ? $data['image_size'] : '500';
				
				parent::save($args);
			}		
	}	
?>