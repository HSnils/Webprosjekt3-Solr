<?php 
	    // ----- SEARCH EXECUTION -----

	if (!empty($_GET["search"])) {

		$userSearch = htmlentities($_GET["search"]);
	    require('../solarium/init.php');
 
	    // create a client instance
	    $client = new Solarium\Client($config);

	    // get a select query instance
	    $query = $client->createSelect();

	    // gets facets
	    $facetSet = $query->getFacetSet();

	    // create a facet query instance and set options
		$facetSet->createFacetQuery('author');

		//highlighting
		//get highlighting component and apply settings
		$hl = $query->getHighlighting();
		$hl->setFields('date, text, title, author, Responsible, Operator');
		$hl->setSimplePrefix('<element style="padding: 2px; background-color: #56a2aa; color: #f3f3f3;"><b>');
		$hl->setSimplePostfix('</b></element>');
		$hl->setSnippets(4);
		

	    // set a query (all prices starting from 12)
	    $query->setQuery($userSearch);

	    // Initiate a DisMax query (Query multiple fields)
	    $dismax = $query->getDisMax();

	    // Select the fields we wish to use the search for
	    $dismax->setQueryFields('title Date author Responsible Operator text id');

	    /*
	    // Example of how you can weigh each field differently.
	    $dismax->setQueryFields('title^3 cast^2 synopsis^1');
	    */

	    // this executes the query and returns the result
	    $resultset = $client->select($query);

	   // var_dump($resultset->getComponet());
	    //highlighting
	    $highlighting = $resultset->getHighlighting();

	    /*// display facet query count
		$count = $resultset->getFacetSet()->getFacet('date')->getValue();
		echo '<hr/>Facet query count : ' . $count;*/

	 	  // ----- RESULTS -----
		// display facet query count
		$count = $resultset->getFacetSet()->getFacet('author')->getValue();
		echo '<hr/>Facet query count : ' . $count;
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
					    foreach ($highlightedDoc as $field => $highlight) {
					        echo '<div class="highlight">'.implode(' (...) ', $highlight) . '';
					        echo "	
					        <div class='itemEnd'>
			        			Skrevet av: <b>". $document->author ."</b> Dato: <b>". substr($document->date, 0,10) ."</b>
			        		</div></div>";
					    }

					}

					echo'<div class="pdficonBox">
							<a class="pdficon" target="_blank" href="../solr-6.6.1/uploads/assignment.pdf"><img src="../images/pdf_icon.svg" alt="CLICK TO OPEN PDF"><br>Click to open</a>';
					
						if($user->is_loggedin()){
		        			
							echo '
							<div class="adminButtonsBox">

									<a href="editmeta.php?id='.$document->id.'">
			        				<div class="adminButtons editButton"> <i class="icon_size material-icons">edit
			        				</i>Edit metadata
			        				</div></a>

			        				<a href="">
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
	    } else{
	    	echo '<div class="treffbox">
	    		Fant ingen treff på <u>'.$_GET['search'].'</u>!
	    	</div>';
	    }
}?>
