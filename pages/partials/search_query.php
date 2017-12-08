<?php 
	    // ----- SEARCH EXECUTION -----

	if (!empty($_GET["search"])) {

		$userSearch = htmlentities($_GET["search"]);
	    require('../solarium/init.php');
 
	    // create a client instance
	    $client = new Solarium\Client($config);

	    // get a select query instance
	    $query = $client->createSelect();

	   /// gets facets
	    $facetSet = $query->getFacetSet();

	    // create a facet query instance and set options
		$facetSet->createFacetField('year')
    		->setField('year');

		//highlighting
		//get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields('title, text');
		$hl->setSimplePrefix('<element style="padding: 2px; background-color: #56a2aa; color: #f3f3f3;"><b>');
		$hl->setSimplePostfix('</b></element>');
		$hl->setSnippets(4);
		

	    // set a query (all prices starting from 12)
	    $query->setQuery($userSearch);

	    // Initiate a DisMax query (Query multiple fields)
	    $dismax = $query->getDisMax();

	    // Select the fields we wish to use the search for
	    $dismax->setQueryFields('title filename year author responsible operator text id');


	    // this executes the query and returns the result
	    $resultset = $client->select($query);


	    //highlighting
	    $highlighting = $resultset->getHighlighting();


	 	  // ----- RESULTS -----
		// display facet query count
		$facet = $resultset->getFacetSet()->getFacet('year');
		echo '<div class="facet">';
		echo '<h5>Treff skrevet i:</h5>';
			foreach($facet as $value => $count) { 

			    echo $value . ' ( <span class="mainColor">' . $count . '</span> )<br/>';
		}
		echo '</div>';
		
	    $antallTreff = $resultset->getNumFound();
	    
	  
	    if($antallTreff != 0) {
	    	// display the total number of documents found by solr
	   		 echo '<div class="treffbox"> Antall treff på <u>'.$_GET['search'].'</u>: '.$antallTreff.'</div>';
		    // show documents using the resultset iterator
		    foreach ($resultset as $document) {

		    	echo "<div class='searchItem'>";

			        echo "<h3>".$document->title.".</h3>";

			  		// highlighting results can be fetched by document id (the field defined as uniquekey in this schema)
					$highlightedDoc = $highlighting->getResult($document->id);
					if ($highlightedDoc) {

						echo '<div class="highlight">';

							//if there is things to highlight do this
							if ($highlightedDoc->count() == true){
								foreach ($highlightedDoc as $field => $highlight) {
							    	if(!empty($highlight)){
							    		echo implode(' (...) ', $highlight);
							    	}
							    } 
							//if nothing to highlight do this
							}else {
						    	echo substr($document->text, 0, 700) ;
							}
							//new using set year
							echo "<div class='itemEnd'> Skrevet av: <b>". $document->author ."</b><span style='margin-left: 20px;'>År: <b>". $document->year ."</b></span>
			        		</div>";

			        		//Old using the date from file
							/*echo "<div class='itemEnd'> Skrevet av: <b>". $document->author ."</b> Dato: <b>". substr($document->date, 0,10) ."</b>
			        		</div>";*/
					  	echo '</div>';

						echo'<div class="pdficonBox">
							<a class="pdficon" target="_blank" href="../solr-6.6.1/uploads/' . $document->filename .'"><img src="../images/pdf_icon.svg" alt="CLICK TO OPEN PDF"><br>Click to open</a>';
					
						if($user->is_loggedin()){
		        			
							echo '
							<div class="adminButtonsBox">

									<a href="editmeta.php?id='.$document->id.'">
			        				<div class="adminButtons editButton"> <i class="icon_size material-icons">edit
			        				</i>Edit metadata
			        				</div></a>

			        				<a href="partials/delete_handler.php?id='.$document->id.'&fileName='. $document->filename .'" class="confirmation">
			        				<div class="adminButtons deleteButton">
			        				<i class="icon_size material-icons">delete</i>
			        				Delete</div>
			        				</a>
		        			</div>';
		        		}
	        		echo '</div>';
					echo "<br>";

					echo "</div>";
		   		}
	   		}
		} else{
	    	echo '<div class="treffbox">
	    		Fant ingen treff på <u>'.$_GET['search'].'</u>!
	    	</div>';
	    }
}?>
<script type="text/javascript">
    $('.confirmation').on('click', function () {
        return confirm('Er du sikker på at du vil slette denne filen?');
    });
</script>
