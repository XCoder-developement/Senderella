<?php
$image = App\Models\Report\Report::find($id)->image_link;
?>


<a href="{{ url($image) }}" target="_blank" class="btn btn-sm btn-hover-bg-light m-0" style="pointer-events: {{ $image ? 'auto' : 'none' }}">
    <i class="fas fa-image"></i> Preview
  </a>
