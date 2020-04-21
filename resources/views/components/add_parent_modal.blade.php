@props(['item'])


<div class="ui modal" id="{{'addparent'.$item->id}}">
    <i class="close icon"></i>

    <div class="content">
        <div class="description">
            <div class="ui header">add parent</div>
        </div>
        <form class="ui form" method="POST" action="{{route('parent', $item)}}" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="field">
                <label>Name</label>
                <input type="text" name="name" placeholder="name" required>
            </div>
            <div class="ui calendar" id="{{'addparent_calendar'.$item->id}}">
                <label>birth</label>
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="Date/Time" name="birth" required>
                </div>
            </div>

            <div class="ui calendar" id="{{'death_calendar'.$item->id}}">
                <label>death</label>
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="Date/Time" name="death">
                </div>
            </div>

            <div class="field">
                <label>gender</label>
                <div class="ui selection dropdown" id="{{'selection_child'.$item->id}}">
                    <input type="hidden" name="gender" required>
                    <i class="dropdown icon"></i>
                    <div class="default text">gender</div>
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