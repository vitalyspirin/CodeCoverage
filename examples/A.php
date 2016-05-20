<?php


class A
{
  public function __construct()
  {
    echo "<!-- constructor for class A has been called -->";
    $a = 2;
    if (1>$a)
    {
      echo "this line shouldn't have executed<br />";
    }
  }
  
}
