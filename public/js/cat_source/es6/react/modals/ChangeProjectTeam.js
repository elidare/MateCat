
class ChangeProjectWorkspace extends React.Component {


    constructor(props) {
        super(props);
        this.state ={
            selectedTeam: this.props.selectedTeam
        };
        this.selectTeamName = '';
    }

    changeTeam() {
        ManageActions.changeProjectTeam(this.state.selectedTeam,  this.props.project);
        APP.ModalWindow.onCloseModal();
    }

    componentDidMount () {
        $(this.dropdown).dropdown();
    }

    selectTeam(teamId) {
        this.setState({
            selectedTeam: teamId
        })
    }

    getTeamsList() {
        let self = this;
        if (this.props.teams) {
            return this.props.teams.map(function (team, i) {
                let selectedClass = (self.state.selectedTeam == team.get('id')) ? 'active' : '';
                self.selectTeamName = (self.state.selectedTeam == team.get('id')) ? team.get('name') : self.selectTeamName;
                return <div className={"item " + selectedClass}
                            key={'team' + team.get('id')}
                            onClick={self.selectTeam.bind(self, team.get('id'))}>
                            <div className="content" >
                                {team.get('name')}
                            </div>
                        </div>;
            });

        } else {
            return '';
        }
    }

    render() {
        let teamsList = this.getTeamsList();
        return <div className="change-team-modal">
            <div className="matecat-modal-top">
                <div className="ui one column grid left aligned">
                    <div className="column">
                        <h2>Move this project:</h2>
                        <div className="move-ribbon">
                            <span className="project-name"> {this.props.project.get('name')}</span>
                            <span className="project-id">(ID: {this.props.project.get('id')})</span>
                        </div>
                    </div>
                </div>
            </div>
            <div className="matecat-modal-middle">
                <div className="ui one column grid left aligned">
                    <div className="column">
                        <h2>Choose new Team</h2>
                        <div className="ui selection fluid bottom dropdown"
                             ref={(dropdown) => this.dropdown = dropdown} >
                            <input type="hidden" name="gender" />
                            <i className="dropdown icon" />
                            <div className="default text">{this.selectTeamName }</div>
                            <div className="menu">
                                {teamsList}
                            </div>
                        </div>
                    </div>
                    <div className="column right aligned">
                        <div className="column">
                            <button className="ui primary button right aligned"
                            onClick={this.changeTeam.bind(this)}>Move</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>;
    }
}


export default ChangeProjectWorkspace ;
