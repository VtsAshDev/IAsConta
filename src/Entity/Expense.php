<?php

namespace App\Entity;

class Expenses
{
    private int $id;
    private 
}



//<id name="id" type="integer" column="id">
//            <generator/>
//        </id>
//
//        <field name="amount" type="decimal" precision="10" scale="2" />
//        <field name="description" length="255" />
//        <field name="createdAt" type="datetime_immutable" />
//
//        <many-to-one field="category" target-entity="App\Entity\Category" inversed-by="expenses">
//            <join-column name="category_id" nullable="false" />
//        </many-to-one>
//
//        <many-to-one field="user" target-entity="App\Entity\User">
//            <join-column name="user_id" nullable="false" on-delete="CASCADE" />
//        </many-to-one>
