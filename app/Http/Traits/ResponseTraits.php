<?php
    namespace App\Http\Traits;
    use Illuminate\Support\Str;
    trait ResponseTraits{
        public function sendErrorResponse($validation)
        {
            return response()->json(['status'=>false,'message'=>'Validation Error','Error'=>$validation->errors()],422);    
        }

        public function sendSuccessResponse($status,$message,$data="")
        {
            return response()->json(['status'=>$status,'message'=>$message,'data'=>$data],200);
        }

        public function sendFailureResponse($message)
        {
            return response()->json(['status'=>false,'message'=>$message],401);
        }

        public function sendExecptionMessage($ex)
        {
            $boolean = Str::contains($ex->getMessage(), 'Duplicate entry');
           if($boolean)
           {
                return response()->json(['status'=>false,'message'=>"Data Duplicate Error"],500);
           }
           else
           {
                return response()->json(['status'=>false,'message'=>$ex->getMessage()],500);
           }
        }

        public function dataNotFound($message)
        {
            return response()->json(['status'=>false,'message'=>$message],404);
        }

        public function sendFilterListData($query, $searching_Fields)
        {  
            if(request()->search){
                // $query->where('name','like',request()->search.'%')
                // ->orWhere('email','like',request()->search)
                // ->orWhere('mobile_number','like',request()->search)
                // ->orWhere('department_name','like',request()->search)
                // ->orWhere('hiredate','like',request()->search)
                // ->orWhere('city','like',request()->search)
                // ->orWhere('gender','like',request()->search)
                // ->orWhere('salary','like',request()->search);
                
                $search = request()->search;
                $query = $query->where(function($query) use($search ,$searching_Fields){
                    foreach ($searching_Fields as $searching_Field) {
                         $query->orWhere($searching_Field,'like',$search.'%');  
                    }
                });
            }

            if(request()->sort)
            {
                $sortOrder = request()->sortOrder == 'desc' ? 'desc': 'asc';
                $query->orderBy(request()->sort,$sortOrder);
            }

            if(request()->salary)
            {
                $query->where('salary','<=',request()->salary);
            }

            $pagination = request()->perPageData ?? 10;
            $list = $query->paginate($pagination);
            //$col = $query->getTableColumns();
            return response()->json(['status'=>true,'message'=>'Data get successfully.','data'=>$list],200);
        }

    }


?>