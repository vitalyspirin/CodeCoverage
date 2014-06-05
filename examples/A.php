<?php


class A
{
  public function __construct()
  {
    echo "<!-- constructor for class A has been called -->";
    if (1>2)
    {
      echo "this line shouldn't have executed<br />";
    }
  }
  
}
