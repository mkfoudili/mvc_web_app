<?php

class HomeController {
    public function index(): void
    {
        echo '<h1>Landing page</h1>';
        // Later → HomeView::render()
    }
}