<!--
<blockquote style='margin-top: 3px;'>
    <p>Seleccione los horarios que desea contratar.</p>
</blockquote>
-->
<div class='ui-widget-header wc-toolbar' style="border: none; margin-bottom: 11px;">
        <button class="btn-small btn" onclick="previo()"><span class="ui-button-icon-primary ui-icon ui-icon-seek-prev"></span></button>
        <div class="btn-group">
            <button class="btn-small btn" onclick="diasAMostrar(2)">2 dias</button>
            <button class="btn-small btn" onclick="diasAMostrar(4)">4 dias</button>
            <button class="btn-small btn" onclick="diasAMostrar(6)">6 dias</button>
        </div>
        <button class="btn-small btn" style="margin-right: 10px" onclick="siguiente()"><span class="ui-button-icon-primary ui-icon ui-icon-seek-next"></span></button>
        <div class="btn-group center-block" style="margin-right: 10px">
            <button class="btn-small btn" onclick="zoomOut()"><i class="fa fa-search-minus"></i></button>
            <button class="btn-small btn" onclick="zoomIn()"><i class="fa fa-search-plus"></i></button>
        </div>
        <div class="btn-group">
            <button class="btn-small btn" onclick="horasManana()">Mañana</button>
            <button class="btn-small btn" onclick="horasFull()">Full</button>
            <button class="btn-small btn" onclick="horasTarde()">Tarde</button>
        </div>
        
    </div>    
       
<div id='calendar<?php echo (isset($editar))? $editar:'';?>'>
</div>
