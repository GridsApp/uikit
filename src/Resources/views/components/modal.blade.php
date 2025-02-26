<div x-cloak  >
    {{-- <div class="absolute inset-0 bg-gray-900 opacity-50" @click="{{$variable}} = false"></div> --}}

    <div x-show="{{$variable}}" class="modal fixed inset-0 z-50 flex items-center justify-center" >

       
     
        <div class="modal-content relative max-w-[500px] bg-white  z-10 rounded-lg " @click.stop @click.away="{{$variable}} = false">
            <div class="modal-header pr-2.5 flex justify-between items-center">
                <h3 class="modal-title">
                    {{$title}}
                </h3>
                <button @click="{{$variable}} = false" class="btn btn-sm btn-icon btn-light btn-clear shrink-0">
                    <i class="fa-regular fa-x"></i>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="border-b border-b-twafieldsgray-200"></div>
               
                {{$slot}}


                <div class="border-b border-b-twafieldsgray-200"></div>

                <div class="flex items-center gap-3 justify-end p-5" id="report_user_modal">

                    @if(isset($action))

                    
                    {!! button($action['label'] , $action['type'] , null , "button" , null , $action['handler']) !!}
                    
                 
                    @endif
                 
                    <button @click="{{$variable}} = false" class="btn btn-sm btn-light text-[12px]">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
