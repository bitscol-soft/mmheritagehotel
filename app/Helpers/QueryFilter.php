<?php
    function filters($query, $request, $items)
    {
        foreach ($items as $key => $item) {
            if ($request->filled($item)) {
                $query->where('date', $request->$item);
            }
        }
        return $query;
    }