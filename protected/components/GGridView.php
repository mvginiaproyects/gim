<?php
Yii::import('zii.widgets.grid.CGridView');
class GGridView extends CGridView {

    	public $footer_count = false;
        public $footer_count_text = '';

    	public function init()
	{
		parent::init();
                if ($this->footer_count)
                        $this->footer_count = $this->dataProvider->getItemCount();
        }
        

	public function renderItems()
	{
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
		{
			echo "<table id='miTabla' class=\"{$this->itemsCssClass}\">\n";
			$this->renderTableHeader();
			ob_start();
			$this->renderTableBody();
			$body=ob_get_clean();
			$this->renderTableFooter();
			echo $body; // TFOOT must appear before TBODY according to the standard.
			echo "</table>";
                        if ($this->footer_count){
                            echo '<div class="well pull-right" style="width: 200px">';
                            echo '<strong>'.$this->footer_count_text.' <span class="badge badge-info">'.$this->dataProvider->getItemCount().'</span></strong>';
                            echo '</div>';
                        }
		}
		else
			$this->renderEmptyText();
	}

}