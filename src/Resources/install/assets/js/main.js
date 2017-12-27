import UIkit from 'uikit';
import Icons from 'uikit/dist/js/uikit-icons';
// import Icons from 'uikit/dist/js/uikit-icons-img';
import './utils/page-loader';

UIkit.use(Icons);

UIkit.notification('UIkit is up and running ...', {
  pos: 'bottom-right',
});
