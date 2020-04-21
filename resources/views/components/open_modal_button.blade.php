@props(['item'])

<div class="action" style="display: flex; justify-content:center">
    <button class="circular ui button" onclick="$('{{'#addparent'.$item->id}}').modal('show'); $('{{'#addparent_calendar'.$item->id}}').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                date: function(date,settings) {
if(!date) return '';
var day = date.getDate();
var month = date.getMonth();
var year = date.getFullYear();
return year + '-' + month + '-' + day;
}
            }}); $('{{'#death_calendar'.$item->id}}').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                date: function(date,settings) {
if(!date) return '';
var day = date.getDate();
var month = date.getMonth();
var year = date.getFullYear();
return year + '-' + month + '-' + day;
}
            }}); $('{{'#selection_child'.$item->id}}').dropdown();">
        add parent
    </button>
</div>