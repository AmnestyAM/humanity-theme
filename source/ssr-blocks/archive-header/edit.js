import ServerSideRender from '@wordpress/server-side-render';

const edit = () => (
  <ServerSideRender block="amnesty-core/archive-header" className="archive-header" />
);

export default edit;
