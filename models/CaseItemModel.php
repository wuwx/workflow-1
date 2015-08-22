<?php

namespace OmniFlow;

/*
 * 	Changes:
 * 		add multi-tenant:	clientId
 * 		add table prefix for each environment
 * 
 */


class caseItemModel extends OmniModel
{
    public static function getTable()
    {
    return self::getPrefix()."caseItem";
    }

    public static function insert(WFCase\WFCaseItem $item)
	{
		
		$item->started=date("Y-m-d H:i:s");
		
		$data=$item->__toArray();
		$id=self::insertRow(self::getTable(),$data);
		$item->id=$id;
		if ($id==null)
		{
			Context::Log(ERROR , "Error: insert failed to retrieve Id");
		}
		return $item;
		
	}
	public static function update(WFCase\WFCaseItem $item)
	{
		if ($item->status==\OmniFlow\enum\StatusTypes::Completed)
			$item->completed=date("Y-m-d H:i:s");

		$data=$item->__toArray();
		
		self::updateRow(self::getTable(),$data,"id=$item->id");
		
		return $item;
	}
    public static function loadCase(WFCase\WFCase $case)
    {
       	$table=self::getTable();
        $caseId=$case->caseId;
	$rows=self::select("select * from $table where caseId =$caseId");
	
	foreach ($rows as $row)
	{
            $item=new WFCase\WFCaseItem($case);
            $item->__fromArray($row);
            $case->items[]=$item; 
	}
    }
    public static function getTableDDL()
    {
        $table=array();
        $table['name']='wf_caseitem';
	$table['sql']="		
                            (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`caseId` int(11) DEFAULT NULL,
				`processNodeId` varchar(45) DEFAULT NULL,
				`type` varchar(45) DEFAULT NULL,
				`subType` varchar(45) DEFAULT NULL,
				`label` varchar(45) DEFAULT NULL,
				`actor` varchar(45) DEFAULT NULL,
				`status` varchar(45) DEFAULT NULL,
				`started` datetime DEFAULT NULL,
				`completed` datetime DEFAULT NULL,
				`result` varchar(45) DEFAULT NULL,
				`timerType` varchar(45) DEFAULT NULL,
				`timer` varchar(45) DEFAULT NULL,
				`timerRepeat` varchar(45) DEFAULT NULL,
				`timerDue` datetime DEFAULT NULL,
				`message` varchar(45) DEFAULT NULL,
				`messageKeys` varchar(450) DEFAULT NULL,
				`signalName` varchar(45) DEFAULT NULL,
				`itemValues` varchar(4500) DEFAULT NULL,
				`caseStatus` varchar(45) DEFAULT NULL,
				`caseStatusDate` datetime DEFAULT NULL,
				`notes` varchar(450) DEFAULT NULL,
				`created` datetime DEFAULT NULL,
				`updated` datetime DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `idx_wf_caseitem_caseId` (`caseId`)
		) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;";
        return $table;
    }
    /*
     * locates and returns the CaseItem that responds to the message with the key values
     * 
     */
    public static function locateMessageItem($messageName,$keyValues)
    {
        $table=self::getTable();
        $messageKeys=  serialize($keyValues);
        
        $rows=self::select("select id,caseId 
                from $table 
                where status not in ('Completed','Terminated')
                and message = '$messageName'
                and messageKeys = '$messageKeys'
                ");       
        return $rows;
    }
}