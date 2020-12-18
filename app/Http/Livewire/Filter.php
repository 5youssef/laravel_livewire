<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Pagination\Paginator;


class Filter extends Component
{
	use WithPagination;

    public $searchTerm;
    public $currentPage = 1;

    public function render()
    {
       return view('livewire.filter', [
            'posts'		=>	Post::postWithUserCommentsTagsImage()->where('title','like', '%'.$this->searchTerm.'%')->paginate(10),
            'count_posts' => Post::count()
    	]);
    }

    public function setPage($url)
    {
        $this->currentPage = explode('page=', $url)[1];
        Paginator::currentPageResolver(function(){
            return $this->currentPage;
        });
    }


    public function like()
    {
        
    }
}
