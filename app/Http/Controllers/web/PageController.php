<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $terms = Page::where('page_name', 'terms-and-conditions')->first();
        $policy = Page::where('page_name', 'privacy-policy')->first();
      
        return view('admin.page.page')
                ->with('terms', $terms)
                ->with('policy', $policy);
    }

    public function saveTerms(Request $request)
    {
        $post_data = $request->all();
      
        $save_terms = Page::where('page_name','terms-and-conditions')->first();
        if($save_terms) {
            $save_terms->page_content = $post_data['editor1'];
            $save_terms->save();
        }else{
            $new_save_terms = new Page();
            $new_save_terms->page_name = 'terms-and-conditions';
            $new_save_terms->page_content = $post_data['editor1'];
            $new_save_terms->save();
        }
        $request->session()->flash('status', 'Terms and Conditions Updated Successfully');
        $request->session()->flash('type', 'success');
        return back();
    }

    public function savePolicy(Request $request)
    {
        $post_data = $request->all();

        $save_policy = Page::where('page_name','privacy-policy')->first();
        if($save_policy) {
            $save_policy->page_content = $post_data['editor2'];
            $save_policy->save();
        }else{
            $new_save_policy = new Page();
            $new_save_policy->page_name = 'privacy-policy';
            $new_save_policy->page_content = $post_data['editor2'];
            $new_save_policy->save();
        }
        $request->session()->flash('status', 'Privacy Policy Updated Successfully');
        $request->session()->flash('type', 'success');
        return back();

    }

    public function term() {
        $terms = Page::where('page_name','terms-and-conditions')->first();
        return view('admin.page.term', compact('terms'));
    }

    public function policy() {
        $terms = Page::where('page_name','privacy-policy')->first();
        return view('admin.page.term', compact('terms'));
    }
}
