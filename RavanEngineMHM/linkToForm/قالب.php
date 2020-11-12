<?php
class  temp
{
    private function getReferIdForLink($docId)
    {
        $db = PDOAdapter::getInstance();

        $ACM = AccessControlManager::getInstance();
        $RID = $ACM->getRoleID();

        $sqlC = "select oa_doc_refer.RowID as referID FROM dm_datastoretable_25 as dm " .
            "INNER JOIN oa_document on (oa_document.rowid = dm.docid) " .
            "INNER JOIN oa_doc_refer on(oa_doc_refer.DocID = oa_document.RowID AND oa_doc_refer.FromRoleID = " . $RID . " and oa_doc_refer.ParentID > 0 ) " .
            "WHERE dm.docid=$docId";

        $db->execute($sqlC);
        $res = $db->fetchAssoc();
        // echo("stringSql:$sqlC");
        $referId = $this->searchFields->sql = $res['referID'];
        if ($referId != "") {
            $this->searchFields->linkMode = "cable";
            return $referId;
        }

        $sqlC = "select oa_doc_refer.RowID as referID FROM dm_datastoretable_25 as dm " .
            "INNER JOIN oa_document on (oa_document.rowid = dm.docid) " .
            "INNER JOIN oa_doc_refer on(oa_doc_refer.DocID = oa_document.RowID AND oa_doc_refer.FromRoleID = " . $RID .
            " AND oa_doc_refer.ToRoleID = " . $RID . ") " .
            "WHERE dm.docid=$docId";

        $db->execute($sqlC);
        $res = $db->fetchAssoc();
        // echo("stringSql:$sqlC");
        $referId = $this->searchFields->sql = $res['referID'];
        $this->searchFields->linkMode = "draft";

        return $referId;


    }
}
