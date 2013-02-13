   </div>
    <footer class="footer clearfix">
      <div class="copyright"><?php echo !empty($config["copyright"]) ? $config["copyright"] : "" ?></div>
	  <nav class="menu_bottom">
        <ul>
        	<?php foreach ($config["menuItems"] as $key => $item): ?>
        		<li<?php echo  (isset($currentPage) && $currentPage == $key) ? ' class="active"' : "" ?>>
        			<a href="<?php echo $item["url"] ?>"><?php echo $item["name"] ?></a>
        		</li>
        	<?php endforeach; ?>
          <?php if (!empty($_SESSION['user']) || !empty($_SESSION['token'])): ?>
            <li>
              <a href="index.php?logout">Logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>

    </footer>

  </div>
</body>
</html>