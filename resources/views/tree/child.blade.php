<li>
    <div class="tree-child">
        <div class="ui card">
            <div class="content">
                <div class="right floated meta">
                    @if($item->gender == "Male")
                    <i class="right large blue inverted circular mars icon"></i>
                    @else
                    <i class="right large red inverted circular venus icon"></i>
                    @endif

                </div>

                <a class="header">{{$item->name}}</a>

            </div>
            <div class="content">

                <div class="description">
                    <i class="birthday cake icon"></i>
                    <span class="date">born: {{$item->birth->format('d.m.Y')}}</span>

                </div>
                <div class="description">
                    <i class="hospital icon"></i>
                    <span class="date">death: @isset($item->death) {{$item->death->format('d.m.Y')}} @endisset</span>

                </div>
                <div class="description">
                    <span class="date">current age: @if(isset($item->death))
                        {{$item->birth->diffInYears($item->death)}}
                        @else {{$item->birth->diffInYears(now())}} @endif

                    </span>

                </div>
            </div>

            @if($item->child_count
            < 2) <x-open_modal_button :item="$item" />
            @endif

        </div>

    </div>

    <x-add_parent_modal :item="$item" />

    <ul>
        @each('tree.child', $item->childRecursive, 'item')
    </ul>




</li>