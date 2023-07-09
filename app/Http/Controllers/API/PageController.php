<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    public function termsConditions()
    {
        $terms = Page::where('page_name', 'terms-and-conditions')->first();

        if(!empty($terms)) {
            return $this->sendResponse( $terms, 'Success' );
        }
        return $this->sendError( [], 'No Data found');
    }

    public function privacyPolicy()
    {
        $terms = Page::where('page_name', 'privacy-policy')->first();

        if(!empty($terms)) {
            return $this->sendResponse( $terms, 'Success' );
        }
        return $this->sendError( [], 'No Data found');
    }
}
