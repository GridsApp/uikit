<?php

namespace twa\uikit\Elements;
use Livewire\Attributes\Modelable;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Select extends Component
{
    #[Modelable]
    public $value;

    public $info;
    public $options;




    public function mount(){

        if (!isset($this->info['multiple'])) {
            $this->info['multiple'] = false;
        }

    }
    public function getOptions($search = null , $id = null){

        switch($this->info['options']['type']){
            case "query" :

                $parent = null;
                if(isset($this->info['options']["parent"])){
                    $parent = $this->info['options']["parent"];
                }

                $options = DB::table($this->info['options']['table']);

                if($parent){

                    $options->select($this->info['options']['table'].'.id as value' ,
                    DB::raw('CONCAT('.$parent['table'].'.'.$parent['field'].', " / " , '.$this->info['options']['table'].'.'.$this->info['options']['field'].' ) as label'),
                    DB::raw('CONCAT("'.$this->info['name'].'", "_" ,'.$this->info['options']['table'].'.id) as identifier'));

                 $options->leftJoin($parent['table'],
                 $this->info['options']['table'].'.'.$parent['key'], $parent['table'].'.id');

                }else{

                    $options->select($this->info['options']['table'].'.id as value' ,
                    $this->info['options']['table'].".".$this->info['options']['field'].' as label',
                    DB::raw('CONCAT("'.$this->info['name'].'", "_" ,'.$this->info['options']['table'].'.id) as identifier'));
                }

                $options->whereNull($this->info['options']['table'].'.deleted_at')
                // ->orWhereIn('id',$this->value)
                ->limit($this->info['query_limit'] ?? 10)


                ->when($search , function($query) use ($search , $parent){

                    $words = explode(" " , $search);

                    foreach($words as $word){
                        $query->where(function($q) use ($word , $parent) {
                            $q->orWhere($this->info['options']['table'].'.'.$this->info['options']['field'] , 'LIKE' , $word.'%');
                            $q->orWhere($this->info['options']['table'].'.'.$this->info['options']['field'] , 'LIKE' , '% '.$word.'%');
                            if($parent){
                                $q->orWhere($parent['table'].'.'.$parent['field'] , 'LIKE' , $word.'%');
                            }
                        });
                    }
                });


                foreach($this->info['options']['conditions'] ?? [] as $condition){
                    apply_condition($options , $condition);
                }
               


                $options = $options->orderBy('label' , 'ASC');

                if(!is_null($id)){
                        if(!is_array($id)){
                            $options->where($this->info['options']['table'].'.'.'id' , $id);
                        }else{
                            $options->whereIn($this->info['options']['table'].'.'.'id' , $id);
                        }
                }


                $options = $options->get()
                ->toArray();



                break;
                case "static" :
                    $options = collect($this->info['options']['list'])->map(function($item){
                        $item['identifier'] = $this->info['name'].'_'.$item['value'];
                        return $item;
                    });

                    if (!is_null($id)) {
                        if (!is_array($id)) {
                        
                            $options = $options->where('value', $id)->first(); 
                            $options = $options ? [$options] : [];
                        } else {
                          
                            $options = $options->whereIn('value', $id)->toArray();
                        }
                    } else {
                        $options = $options->toArray();
                    }
                    break;
                    default :
                    $options = [];
        }


        $this->skipRender();

        return response()->json($options);

    }


    public function render()
    {
        $options = [];
        return view('UIKitView::components.form.select' , ['options' =>  $options ]);
    }
}
