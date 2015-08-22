<?php

namespace OmniFlow;

/*
 * 	Changes:
 * 		add multi-tenant:	clientId
 * 		add table prefix for each environment
 * 
 */
class ProcessItemModel extends OmniModel
{
    public static function getTable()
    {
        return self::getPrefix()."processItem";
    }
    
    public static function getMessageItem($messageName)
    {
       $table=self::getTable();
       $pTable=ProcessModel::getTable();
       return self::select("Select p.name,i.processNodeId,i.type,i.messageKeys
                from $table i
                join $pTable p on p.id=i.processId 
                where message='.$messageName'");        
    }
    public static function getTableDDL()
    {
        $table=array();
        $table['name']=self::getTable();
	$table['sql']="		
		 (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`processId` int(11) DEFAULT NULL,
				`processNodeId` varchar(45) DEFAULT NULL,
				`type` varchar(45) DEFAULT NULL,
				`subType` varchar(45) DEFAULT NULL,
				`label` varchar(45) DEFAULT NULL,
				`timerType` varchar(45) DEFAULT NULL,
				`timer` varchar(45) DEFAULT NULL,
				`timerRepeat` varchar(45) DEFAULT NULL,
				`timerDue` datetime DEFAULT NULL,
				`message` varchar(45) DEFAULT NULL,
				`messageKeys` varchar(450) DEFAULT NULL,
				`signalName` varchar(45) DEFAULT NULL,
				`created` datetime DEFAULT NULL,
				`updated` datetime DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `idx_wf_processitem_caseId` (`processId`)
		) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;";
        return $table;

    }
}
