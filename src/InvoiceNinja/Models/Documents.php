<?php namespace InvoiceNinja\Models;

class Documents extends AbstractModel
{
    public static $route = 'documents';

    /**
     * @param $expense_id
     * @return array
     */
    public static function allForExpense($expense_id)
    {
        $documents = self::all();

        $result = [];
        foreach ($documents as $document) {
            if($document->expense_id === $expense_id){
                $result[] = $document;
            }
        }

        return $result;
    }
}
