<div class="header__navItem">
  Bonjour, {{ Auth::guard("admin")->user()->email }} !
  <a href="#" class="header__navBtnInvisible header__navBtnInvisible--ico" aria-label="Logout" title="Logout"
    data-submit="logout-form">
    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="32" viewBox="0 0 26 32">
      <path fill="#4A5B69"
        d="M10.182.505C7 1.286 3.815 2.062.63 2.832c-.276.068-.29.23-.29.45C.333 6.612.325 9.94.314 13.27l-.028 14.46c0 .29.067.433.363.533 3.591 1.205 7.18 2.42 10.766 3.643.098.032.198.055.343.096.012-.232.032-.437.033-.64.034-3.454.068-6.906.1-10.36.062-5.864.124-11.726.188-17.59.012-1.137.035-2.272.052-3.41h-.062c-.628.166-1.254.348-1.886.504zM10.75 16.9c-.308.312-.756.292-1.03-.068-.318-.412-.36-.875-.156-1.35.128-.3.335-.526.793-.546.085.048.27.109.394.23.438.419.425 1.305 0 1.733h-.001zm7.105-13.217c.279 0 .379.066.375.363-.014 1.9-.005 3.798-.005 5.698v.641c.5 0 1.008.008 1.5-.014.048 0 .125-.192.125-.296.01-1.179.008-2.357.008-3.537V2.406c0-.187 0-.335-.272-.332-1.898.01-3.797.005-5.697.007a1.717 1.717 0 00-.16.014v1.593h.375c1.247 0 2.497.007 3.751-.005zm1.668 17.942c-.424.017-.858.005-1.295.005v5.683h-4.75v1.642c.124.005.19.015.282.015 1.917 0 3.835-.005 5.75.008.283 0 .349-.087.348-.353a845.471 845.471 0 010-6.662c.005-.26-.08-.345-.335-.338z" />
      <path fill="#4A5B69" d="M25.971 16l-6.476-3.74v2.49h-4.437v2.5h4.437v2.49z" />
    </svg>
  </a>
</div>