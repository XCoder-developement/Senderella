<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TitleResource;
use App\Models\BlockReason\BlockReason;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class BlockReasonController extends Controller
{
    use ApiTrait;
    public function fetch_block_reasons(){
        $block_reasons = BlockReason::get();
        $data=TitleResource::collection($block_reasons);
        $msg='fetch_block_reasons';
        return $this->dataResponse($msg ,$data ,200);
    }
}
