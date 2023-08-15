<div class="form-group">
    <label>Enable User Attendence <span style="opacity: 0.5; font-style: italic;"></span></label>
    <input class="form-control" type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" placeholder="" name="user_active">
</div>
<div class="form-group">
    <label>Before Shift Time <span style="opacity: 0.5; font-style: italic;">Can Check In Before Shift Time</span></label>
    <input class="form-control" type="time" placeholder="Enter Start Time" name="over_time_start">
</div>
<div class="form-group">
    <label>Shift Start Time <span style="opacity: 0.5; font-style: italic;">Enter Shift Time</span></label>
    <input class="form-control" type="time" placeholder="Enter Start Time" name="start_time">
</div>
<div class="form-group">
    <label>Shift End Time <span style="opacity: 0.5; font-style: italic;">Enter Shift End Time</span></label>
    <input class="form-control" type="time" placeholder="Enter End Time" name="end_time">
</div>
<div class="form-group">
    <label>After Shift End Time <span style="opacity: 0.5; font-style: italic;">Can Check Out After Shift Time</span></label>
    <input class="form-control" type="time" placeholder="Enter End Time" name="over_time_end">
</div>
<div class="form-group">
    <label>Distance Measure <span style="opacity: 0.5; font-style: italic;"></span></label>
    <input class="form-control" type="text" placeholder="Enter Distance" name="distance_measure" value="0">
</div>
<div class="form-group">
    <label>Sellary Per Minute <span style="opacity: 0.5; font-style: italic;"></span></label>
    <input class="form-control" type="text" placeholder="Enter Per Minute Sellay" name="per_minute_sellary" value="0">
</div>
<div class="form-group">
    <label>Over Time Per Minute <span style="opacity: 0.5; font-style: italic;"></span></label>
    <input class="form-control" type="text" placeholder="Enter Per Minute Sellay" name="over_time_per_minute_sellary" value="0">
</div>
@push('scripts')

<script type="text/javascript">
    
    
</script>
@endpush
