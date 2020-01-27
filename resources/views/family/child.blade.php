<li>

    <div class="tf-nc ui card" style="display: inline-block;
    transform: rotateX(180deg);">

        <div class=" content">

            <div class="right floated meta">
                @if($item->gender == 'Male')
                <i class="right large blue inverted circular mars icon"></i>
                @else
                <i class="right large red inverted circular  venus icon"></i>
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
                age: @if(isset($item->death)) {{$item->birth->diffInYears($item->death)}} @else
                {{$item->birth->diffInYears(now())}} @endif
            </div>
        </div>

        @if($item->childrenRecursive()->count() < 2) <div class="action" style="display: flex;
                justify-content: center;">
            <button class="circular ui button" onclick="$('{{'#add_child'.$item->id}}').modal('show'); $('{{'#add_calendar_child'.$item->id}}').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                date: function (date, settings) {
                  if (!date) return '';
                  var day = date.getDate();
                  var month = date.getMonth() + 1;
                  var year = date.getFullYear();
                  return year + '-' + month + '-' + day;
                }
            }}); $('{{'#death_calendar_child'.$item->id}}').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                date: function (date, settings) {
                  if (!date) return '';
                  var day = date.getDate();
                  var month = date.getMonth() + 1;
                  var year = date.getFullYear();
                  return year + '-' + month + '-' + day;
                }
              }}); $('{{'#selection_child'.$item->id}}').dropdown();"
                style="display:flex; justify-self: center; align-self:center; justify-content:center">
                <i class="icon add"></i> Add Parent
            </button>

    </div>
    @endif
    </div>


    <div class="ui modal" id="{{'add_child'.$item->id}}">
        <i class="close icon"></i>

        <div class=" content">
            <div class="description">
                <div class="ui header">add parent</div>

            </div>
            <form class="ui form" method="POST" action="{{route('family/parent', $item)}}" autocomplete="off">
                @csrf
                @method('put')



                <div class="field">
                    <label>name</label>
                    <input type="text" name="name" placeholder=" Name" required>
                </div>
                <div class="ui calendar" id="{{'add_calendar_child'.$item->id}}">
                    <label>birth</label>
                    <div class="ui input left icon">

                        <i class="calendar icon"></i>
                        <input type="text" name="birth" required>
                    </div>
                </div>

                <div class="ui calendar" id="{{'death_calendar_child'.$item->id}}">
                    <label>death</label>
                    <div class="ui input left icon">

                        <i class="calendar icon"></i>
                        <input type="text" name="death">
                    </div>
                </div>

                <div class="field">
                    <label>gender</label>
                    <div class="ui selection dropdown" id="{{'selection_child'.$item->id}}">
                        <input type="hidden" name="gender" required>
                        <i class="dropdown icon"></i>
                        <div class="default text">Gender</div>
                        <div class="menu">
                            <div class="item" data-value="Male">Male</div>
                            <div class="item" data-value="Female">Female</div>
                        </div>
                    </div>
                </div>



                <button class="ui button" type="submit">Submit</button>
            </form>

        </div>

    </div>



    <ul>
        @foreach($item->children as $item)

        @include('family.child', $item)

        @endforeach
    </ul>
</li>