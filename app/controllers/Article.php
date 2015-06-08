<?php

class Article extends Controller
{
	public function index($url = '')
	{
		if (isset($url)){
			$getArticle = $this->model('mod_Article'); //require the model
			$article = $getArticle->getArticleFromUrl($url);

			if (count($article)) {
				$cut_day_hour = explode(' ', $article[0]['creation_date']);
				$day = explode('-', $cut_day_hour[0]);
				$hour = explode(':', $cut_day_hour[1]);
				$article[0]['creation_date'] = 'Publié le '.$day[2].'/'.$day[1].' à '.$hour[0].'h'.$hour[1];
				
				$this->view('article', $article);
			}
			else{
				$this->view('error');
			}
		}
		else{
			$this->view('error');
		}
	}

	public function search()
	{
		if (isset($_POST['search'])) {
			$getArticle = $this->model('mod_Article'); //require the model
			$articles = $getArticle->getArticlesByTags($_POST['search']);

			if (count($articles)) {
				foreach ($articles as $key => $article) { //Date layout
					$cut_day_hour = explode(' ', $article['creation_date']);
					$day = explode('-', $cut_day_hour[0]);
					$hour = explode(':', $cut_day_hour[1]);

					$articles[$key]['creation_date'] = 'Publié le '.$day[2].'/'.$day[1].' à '.$hour[0].'h'.$hour[1];
					
					require_once '../app/plugins/truncate/truncate.php';
					$articles[$key]['content'] = truncate($articles[$key]['content'], 500);
				}
				$this->view('search', $articles);
			}
			else{
				$this->view('error');
			}
		}
	}
}